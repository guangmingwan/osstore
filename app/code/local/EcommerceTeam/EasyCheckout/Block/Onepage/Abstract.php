<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.2
 *
 */

	class EcommerceTeam_EasyCheckout_Block_Onepage_Abstract extends Mage_Checkout_Block_Onepage_Abstract{
		
		protected $helper;
		
		public function __construct(){
			$this->helper = Mage::helper('ecommerceteam_echeckout');
		}
		
		public function getConfigData($node){
			return $this->helper->getConfigData($node);
		}
		
		public function getCountryHtmlSelect($type){
	        $countryId = $this->getAddress()->getCountryId();
	        if (is_null($countryId)) {
	            $countryId = Mage::getStoreConfig('general/country/default');
	        }
	        $select = $this->getLayout()->createBlock('core/html_select')
	            ->setName($type.'[country_id]')
	            ->setId($type.':country_id')
	            ->setTitle($this->__('Country'))
	            ->setClass('validate-select')
	            ->setValue($countryId)
	            ->setOptions($this->getCountryOptions());

	        return $select->getHtml();
	    }
	    public function isThreeColsMode(){
	    	return Mage::helper('ecommerceteam_echeckout')->getConfigFlag('options/checkoutmode');
	    }
	    public function getAddressesHtmlSelect($type)
	    {
	        if ($this->isCustomerLoggedIn()) {
	            $options = array();
	            foreach ($this->getCustomer()->getAddresses() as $address) {
	                $options[] = array(
	                    'value'=>$address->getId(),
	                    'label'=>$address->format('oneline')
	                );
	            }

	            $addressId = $this->getAddress()->getCustomerAddressId();
	            
	            if (empty($addressId)) {
	                if ($type=='billing') {
	                    $address = $this->getCustomer()->getPrimaryBillingAddress();
	                } else {
	                    $address = $this->getCustomer()->getPrimaryShippingAddress();
	                }
	                if ($address) {
	                    $addressId = $address->getId();
	                }
	            }

	            $select = $this->getLayout()->createBlock('core/html_select')
	                ->setName($type.'_address_id')
	                ->setId($type.'-address-select')
	                ->setClass('address-select')
	                ->setExtraParams('onchange="'.$type.'.newAddress(!this.value)"')
	                ->setValue($addressId)
	                ->setOptions($options);

	            $select->addOption('', Mage::helper('checkout')->__('New Address'));

	            return $select->getHtml();
	        }
	        return '';
	    }
	    
	    public function customerHasAddresses(){
	    	
	    	if($this->helper->getConfigFlag('options/address_book_enabled')){
	    	
	    		return parent::customerHasAddresses();
	    	
	    	}
	    	
	    	return false;
	    	
	    }
		
	}