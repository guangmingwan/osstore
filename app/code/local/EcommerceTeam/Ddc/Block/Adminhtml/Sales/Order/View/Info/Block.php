<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/
	
	class EcommerceTeam_Ddc_Block_Adminhtml_Sales_Order_View_Info_Block extends Mage_Core_Block_Template{
		
		protected $order;
		
		public function getOrder(){
			if(is_null($this->order)){
				
		        if(Mage::registry('current_order')) {
		        	
		            $order = Mage::registry('current_order');
		            
		        }elseif(Mage::registry('order')) {
		        	
		            $order = Mage::registry('order');
		            
		        }else{
		        	
		        	$order = new Varien_Object();
		        	
		        }
		        
		        $this->order = $order;
			}
			
			return $this->order;
		}
		
		
	}