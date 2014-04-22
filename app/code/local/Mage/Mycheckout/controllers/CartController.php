<?php
class Mage_Mycheckout_CartController extends Mage_Core_Controller_Front_Action
{
	public function preDispatch()
    {
        parent::preDispatch();
    	

    }
	public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
	}
	
	protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
	public function addsucessAction()
	{
		if ($productId = (int) $this->getRequest()->getParam('product')) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
        	Mage::register('current_product', $product);
        	//$message = $this->__('%s was successfully added to your shopping cart.', $product->getName());
    		//$this->_getSession()->addSuccess($message);        
		}
                
    	
		$this->loadLayout();     
		$this->renderLayout();
	}
}