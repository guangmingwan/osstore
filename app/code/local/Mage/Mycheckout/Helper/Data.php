<?php 
class Mage_Mycheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getAddSucessUrl($product, $additional = array())
    {
		//$continueShoppingUrl = $this->getCurrentUrl();

        $params = array(
            'product' => $product->getId()
        );

        if ($this->_getRequest()->getRouteName() == 'checkout'
            && $this->_getRequest()->getControllerName() == 'cart') {
            $params['in_cart'] = 1;
        }

        if (count($additional)){
            $params = array_merge($params, $additional);
        }

        return $this->_getUrl('mycheckout/cart/addsucess/', $params);
    }
    public function getForwardParams($product, $additional = array())
    {
    	$params = array(
            'product' => $product->getId()
        );

        if ($this->_getRequest()->getRouteName() == 'checkout'
            && $this->_getRequest()->getControllerName() == 'cart') {
            $params['in_cart'] = 1;
        }

        if (count($additional)){
            $params = array_merge($params, $additional);
        }
		$urlParams = array("mycheckout","cart","addsucess",$params);
        //return $this->_getUrl('mycheckout/cart/addsucess/', $params);
        return $urlParams;
        
    }
}
?>