<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Social
*/
class Amasty_Social_Block_Button_Abstract extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        if ( !Mage::getStoreConfig("amsocial/twitter/enabled")
               && !Mage::getStoreConfig("amsocial/facebook/enabled")
                    && !Mage::getStoreConfig("amsocial/google/enabled") )
        {
            return '';
        }
        return parent::_toHtml();
    }
}