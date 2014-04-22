<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/


class EcommerceTeam_Ddc_Block_Shipping_Method_Additional extends Mage_Checkout_Block_Onepage_Shipping_Method_Additional
{
    
    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
     
     
     
    protected function _afterToHtml($html)
    {
    	
    	
    	if((int)Mage::getStoreConfig('checkout/deliverydatecomment/enabled_comment') == 1){
	    	
	    	if($block = $this->getLayout()->createBlock('ecommerceteam_ddc/form')){
	    		
	    		$html = $block->setTemplate('ecommerceteam/ddc/comment.phtml')->toHtml().$html;
	    		
	    	}
	    	
    	}
    	
    	if((int)Mage::getStoreConfig('checkout/deliverydatecomment/enabled_deliverydate') == 1){
	    	
	    	if($block = $this->getLayout()->createBlock('ecommerceteam_ddc/form')){
	    		
	    		$html = $block->setTemplate('ecommerceteam/ddc/date.phtml')->toHtml().$html;
	    		
	    	}
	    	
    	}
    	
        return $html;
    }

}
