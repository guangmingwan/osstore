<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/

class EcommerceTeam_Ddc_Model_Resource_Mysql4_Order extends Mage_Core_Model_Mysql4_Abstract{
	
    public function _construct(){
    	
        $this->_init('ecommerceteam_ddc/order', 'entity_id');
        
    }
}