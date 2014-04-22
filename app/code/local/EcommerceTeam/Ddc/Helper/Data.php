<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/

class EcommerceTeam_Ddc_Helper_Data extends Mage_Core_Helper_Abstract{
	public function getConfigData($node){
		
		return Mage::getStoreConfig(sprintf('checkout/deliverydatecomment/%s', $node));
		
	}
}
