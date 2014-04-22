<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.2
 *
 */
	
	class EcommerceTeam_EasyCheckout_Block_Onepage extends EcommerceTeam_EasyCheckout_Block_Onepage_Abstract{
		
		public function getCmsBlockHtml(){
	        if (!$this->getData('cms_block_html')) {
	            $html = $this->getLayout()->createBlock('cms/block')
	                ->setBlockId($this->helper->getConfigData('options/cms_block'))
	                ->toHtml();
	            $this->setData('cms_block_html', $html);
	        }
	        return $this->getData('cms_block_html');
	    }
	    
	    
	    /**
	     * Preparing global layout
	     *
	     * You can redefine this method in child classes for changin layout
	     *
	     * @return Mage_Core_Block_Abstract
	     */
	    protected function _beforeToHtml()
	    {
	    	
	    	if($this->isThreeColsMode()){
	    	$this->setTemplate('ecommerceteam/echeckout/checkout-3columns.phtml');
	    	}
	    	
	        return $this;
	    }
		
	}