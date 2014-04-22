<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.2
 *
 */

class EcommerceTeam_EasyCheckout_Helper_Data extends Mage_Core_Helper_Abstract{
	
	protected $mode;
	protected $_config_cache = array();
	protected $_onepage;
	protected $_checkout;
	protected $_geoip_record;
	
	public function getCheckout()
    {
        if (empty($this->_checkout)) {
            $this->_checkout = Mage::getSingleton('checkout/session');
        }
        return $this->_checkout;
    }
	
	public function getOnepage(){
		if (is_null($this->_onepage)) {
            $this->_onepage = Mage::getSingleton('checkout/type_onepage');
        }
		return $this->_onepage;
	}
	
    public function getConfigData($xmlnode){
    	
    	if(!isset($this->_config_cache[$xmlnode])){
    		$this->_config_cache[$xmlnode] = Mage::getStoreConfig('checkout/'.$xmlnode);
    	}
    	return $this->_config_cache[$xmlnode];
	}
	public function getConfigFlag($xmlnode){
    	
    	if(!isset($this->_config_cache[$xmlnode])){
    		$this->_config_cache[$xmlnode] = Mage::getStoreConfigFlag('checkout/'.$xmlnode);
    	}
    	return $this->_config_cache[$xmlnode];
	}
	
	public function getDefaultCountryId(){
		
	    return Mage::getStoreConfig('general/country/default');
	    
	}
	
	public function initSingleShippingMethod($address){
		
		$rates = $address->getGroupedAllShippingRates();
		
        if(count($rates) == 1){
						
			foreach($rates as $rate_code=>$methods){
				
				if(count($methods) == 1){
					foreach($methods as $method){
						
						$address->setShippingMethod($method->getCode());
					}
				}
				
				break;
			}
			
		}
		
		return $address;
		
	}
	
	public function differentShippingEnabled(){
		
		return (bool)$this->getConfigData('options/different_shipping_enabled');
		
	}
	
	public function couponEnabled(){
		
		return (bool)$this->getConfigData('options/coupon_enabled');
	}
	public function showSubscribe(){
		
		if((bool)$this->getConfigData('options/subscibe_enabled')){
			
			$session = Mage::getSingleton('customer/session');
			
			if($session->isLoggedIn()){
				
				if(Mage::getModel('newsletter/subscriber')->loadByCustomer($session->getCustomer())->getStatus() == 1){
					
					return false;
					
				}
				
			}
			
			return true;
			
		}
	}
	
	
	
	public function shippingSameAsBilling(){
		
		
		if($this->differentShippingEnabled()){
			
			if(is_null($this->getCheckout()->getShippingSameAsBilling())){
	    		return true;
	    	}
	    	
	    	return (bool)($this->getCheckout()->getShippingSameAsBilling());
	    	
    	}else{
    		return true;
    	}
		
	}
	
	public function getDefaultPaymentMethod(){
		
		$onepage = Mage::getSingleton('checkout/type_onepage');
		
		$quote = $onepage->getQuote();
        $store = $quote ? $quote->getStoreId() : null;
        $methods = Mage::helper('payment')->getStoreMethods($store, $quote);
        
        return array_shift($methods);
        
	}
	public function getGeoipRecord(){
		
		if(is_null($this->_geoip_record)){
			
			if(
				extension_loaded('mbstring') &&
				(
				$this->getConfigData('options/geoip_country') || 
				$this->getConfigData('options/geoip_state') ||
				$this->getConfigData('options/geoip_post') ||
				$this->getConfigData('options/geoip_city')
				)
				)
			{
				
				$datafile = Mage::getBaseDir('media').'/ecommerceteam/geoip/'.$this->getConfigData('options/geoip_file');
				
				if(!is_readable($datafile) || !is_file($datafile)){
					$datafile = Mage::getBaseDir('media').'/ecommerceteam/geoip/default/GeoLiteCity.dat';
				}
				
				if(is_readable($datafile) && is_file($datafile)){
				
					try{
						$this->_geoip_record = EcommerceTeam_EasyCheckout_Model_GeoIP_Core::getInstance($datafile, EcommerceTeam_EasyCheckout_Model_GeoIP_Core::GEOIP_STANDARD)
						->geoip_record_by_addr($_SERVER['REMOTE_ADDR']);
					}catch(Exception $e){
						$this->_geoip_record = false;
					}
				
				}else{
					$this->_geoip_record = false;
				}
				
			}else{
				$this->_geoip_record = false;
			}
		
		}
		
		//print_r($this->_geoip_record);
		
		return $this->_geoip_record;
		
	}
	
}
