<?php
class Mage_Mycheckout_Model_Observer
{
	public function __construct()
	{

    }
    public function checkout_cart_add_product_complete($observer)
    {
    	//die("checkout_cart_add_product_complete");
    	
    	
    	$product = $observer->product;
    	$request = $observer->request;
    	if($request->getPost("isbuynow") == 1)
    	{
	    	$response = $observer->response;
	    	Mage::register('current_product', $product);
	    	$url = Mage::Helper("mycheckout/data")->getAddSucessUrl($product);
	    	$observer->getEvent()->getResponse()->setRedirect($url);
	        Mage::getSingleton('checkout/session')->setNoCartRedirect(true);
	        $message = $product->getName() .' was successfully added to your shopping cart.';
	        Mage::getSingleton('core/session')->setMycheckoutMessage($message);
	        
	        //$response->setRedirect(Mage::getUrl("checkout/onepage/"));
		$response->setRedirect(Mage::getUrl("checkout/cart/"));
    	}
    }
}
