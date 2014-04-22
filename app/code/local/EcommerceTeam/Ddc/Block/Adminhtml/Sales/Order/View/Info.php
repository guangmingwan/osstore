<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/
	
	class EcommerceTeam_Ddc_Block_Adminhtml_Sales_Order_View_Info extends Mage_Adminhtml_Block_Sales_Order_View_Info{
		
		protected function _afterToHtml($html)
	    {
	    	
	    	$block = $this->getChild('order_info.ecommerceteam');
	    	
	        return parent::_afterToHtml($html . ($block ? $block->toHtml() : ''));
	    }
		
	}