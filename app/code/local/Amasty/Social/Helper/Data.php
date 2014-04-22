<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Social
*/
class Amasty_Social_Helper_Data extends Mage_Core_Helper_Abstract
{
   public function getCouponCode()
   {
       return Mage::getStoreConfig('amsocial/coupon/code');
   }
}