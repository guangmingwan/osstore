<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.2
 *
 */

class EcommerceTeam_EasyCheckout_Model_System_Conf_Source_Cctypes extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{
	
	protected $_options;
	
    /**
     * Options getter
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
        	
        	$types = Mage::getSingleton('payment/config')->getCcTypes();
            
                
            foreach ($types as $code=>$name) {
            	
            	$this->_options[] = array('value'=>$code, 'label'=>$name);
            	/*
                if (!in_array($code, $availableTypes)) {
                    unset($types[$code]);
                }*/
            }
        
        	/*
            $this->_options = Mage::getResourceModel('cms/block_collection')
                ->load()
                ->toOptionArray();
            */
            //array_unshift($this->_options, array('value'=>'', 'label'=>Mage::helper('ecommerceteam_echeckout')->__('Please select a static block ...')));
        }
        return $this->_options;
    }
}