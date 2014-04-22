<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Mobile
 * @version    1.6.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Mobile_SwitchController extends Mage_Core_Controller_Front_Action
{
    /**
     * List of not supported path parts
     * @var array
     */
    protected $_notSupportedPath = array(
        '/wishlist/',
        '/review/',
    );

    /**
     * Show notice to customer
     * @param string $message
     * @return AW_Mobile_SwitchController 
     */
    protected function _addNotice($message)
    {
        Mage::getSingleton('core/session')->addNotice($message);
        return $this;
    }

    /**
     * Is refferer url allowed to show in Mobile
     * @return boolean
     */
    protected function _checkForSupport()
    {
        $refUrl  = $this->_getRefererUrl();
        $result = true;

        foreach ($this->_notSupportedPath as $part){
            if (strpos($refUrl, $part) !== false){
                $result = false;
            }
        }
        return $result;
    }


    /**
     * Redirect back
     * @param boolean $isMobile
     * @return null
     */
    protected function _goBack($isMobile = false)
    {
        if ($this->_checkForSupport() || !$isMobile) {
            $this->_redirectReferer();
        } else {
            $this->_addNotice(Mage::helper('awmobile')->__('The opportunity of using this URL is not supported yet'));
            $this->_redirect('');
        }
        
        return;
    }

    /**
     * Retrives helper
     * @return AW_Mobile_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('awmobile');
    }
    
    protected function _generateBackLink() {
        
        $refererUrl = $this->_getRefererUrl();
        if (empty($refererUrl)) {
            $refererUrl = Mage::getBaseUrl();
        }        
        $symbol = "?";
        if(preg_match("#\?#is", $refererUrl)) {
            $symbol = "&";
        }
         
       return $refererUrl . "{$symbol}key=" . time() . mt_rand();
       
    }

    public function toMobileAction()
    {
        $this->_helper()->setShowDesktop(false);   
        
        $refererUrl = $this->_getRefererUrl();
        if (empty($refererUrl)) {
            $refererUrl = Mage::getBaseUrl();
        }
        Mage::app()->getResponse()->setRedirect($this->_generateBackLink());
    }

    public function toDesktopAction()
    {        
        $this->_helper()->setShowDesktop(true);        
        Mage::app()->getResponse()->setRedirect($this->_generateBackLink());
    }
}