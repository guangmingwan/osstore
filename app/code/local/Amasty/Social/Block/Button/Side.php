<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Social
*/
class Amasty_Social_Block_Button_Side extends Amasty_Social_Block_Button_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amsocial/button/side.phtml');
    }
    
    public function getImageUrl()
    {
        $url = Mage::getStoreConfig('amsocial/general/side_image');
        if ($url)
        {
            $url = Mage::getBaseUrl('media') . 'amsocial/' . $url;
        }
        return $url;
    }
}