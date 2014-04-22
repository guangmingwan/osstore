<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/

class EcommerceTeam_Ddc_Model_Quote extends Mage_Core_Model_Abstract{
	
	public function _construct(){
		
        parent::_construct();
        
        $this->_init('ecommerceteam_ddc/quote');
        
    }
	
}