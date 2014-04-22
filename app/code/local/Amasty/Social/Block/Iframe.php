<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Social
*/
class Amasty_Social_Block_Iframe extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amsocial/iframe.phtml');
    }
    
    public function getIframeUrl()
    {
        $url = $this->getUrl('social/popup');
        if (isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'])
        {
            $url = str_replace('http:', 'https:', $url);
        }
        return $url;
    }
}