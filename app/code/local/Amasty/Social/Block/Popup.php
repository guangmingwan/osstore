<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Social
*/
class Amasty_Social_Block_Popup extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amsocial/popup.phtml');
    }
    
    public function isEnabled($type)
    {
        return Mage::getStoreConfig("amsocial/$type/enabled");
    }
    
    public function getCouponUrl()
    {
        return $this->getUrl('social/popup/coupon');
    }
    
    public function getTwitterUrl()
    {
        return Mage::getStoreConfig('amsocial/twitter/url');
    }
    
    public function getTwitterCountUrl()
    {
        return Mage::getStoreConfig('amsocial/twitter/counturl');
    }
    
    public function getTwitterVia()
    {
        return Mage::getStoreConfig('amsocial/twitter/via');
    }
    
    public function getTwitterRelated()
    {
        return Mage::getStoreConfig('amsocial/twitter/related');
    }
    
    public function getTwitterText()
    {
        return Mage::getStoreConfig('amsocial/twitter/text');
    }
    
    public function getFacebookUrl()
    {
        return Mage::getStoreConfig('amsocial/facebook/url');
    }
    
    public function getFacebookAppId()
    {
        return Mage::getStoreConfig('amsocial/facebook/appid');
    }
    
    public function getGoogleUrl()
    {
        return Mage::getStoreConfig('amsocial/google/url');
    }
    
    public function getHeader()
    {
        return Mage::getStoreConfig('amsocial/general/header');
    }
    
    public function getMessage()
    {
        return Mage::getStoreConfig('amsocial/general/note');
    }
    
    public function getCookieCoupon()
    {
        $coupon = Mage::app()->getCookie()->get('amsocial_code');
        return $coupon;
    }
}