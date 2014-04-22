<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Social
*/
class Amasty_Social_PopupController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function couponAction()
    {
        /*$platform = Mage::app()->getRequest()->getParam('platform');
        if (!in_array($platform, array('platform_twitter', 'platform_fb', 'platform_gplus')))
        {
            $this->getResponse()->setBody();
        } else 
        {
            $result = Mage::helper('amsocial')->getCouponCode();
            Mage::app()->getCookie()->set('amsocial_code', $result, 3600 * 48); // set cookie for 48 hours
            $this->getResponse()->setBody(
                $result
            );
        }*/
        $sl0=Mage::app()->getRequest()->getParam(base64_decode('cGxhdGZvcm0='));if(!in_array($sl0,array(base64_decode('cGxhdGZvcm1fdHdpdHRlcg=='),base64_decode('cGxhdGZvcm1fZmI='),base64_decode('cGxhdGZvcm1fZ3BsdXM=')))){$this->getResponse()->setBody();}else{$il1=Mage::helper(base64_decode('YW1zb2NpYWw='))->getCouponCode();Mage::app()->getCookie()->set(base64_decode('YW1zb2NpYWxfY29kZQ=='),$il1,3600*48);$this->getResponse()->setBody($il1);}
    }
}