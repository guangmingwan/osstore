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


/**
 * Predispatch observer
 */
class AW_Mobile_Model_Observer
{
    /**
     * Trget is Desktop
     */

    const TARGET_DESKTOP = 'desktop';

    /**
     * Target is Mobile
     */
    const TARGET_MOBILE = 'mobile';
    
    
    const MOBILE_COOKIE = 'aw_mobile_cookie';
    /**
     * Merge JS config path
     */
    const XML_PATH_MERGE_JS = 'dev/js/merge_files';
    const COOKIE_NAME = 'iphone_store';

    /**
     * Params to change in config
     * @var array
     */
    protected $_initParams = array('layout', 'template', 'skin');

    /**
     * Retrives data helper
     * @return AW_Mobile_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('awmobile');
    }

    /**
     * retrives singleton instance of Design Package
     * @return AW_Mobile_Model_Core_Design_Package
     */
    protected function _getDesignPackage()
    {
        return Mage::getSingleton('core/design_package');
    }

    /**
     * Initialize mobile theme on fly
     * @return AW_Mobile_Model_Observer
     */
    protected function _initMobile()
    {
        Varien_Profiler::start('aw::mobile::init_mobile');
        $this->_getDesignPackage()->setPackageName($this->_helper()->getMobilePackage());
        foreach ($this->_initParams as $type) {
//            Mage::app()->getStore()->setConfig('design/theme/'.$type, $this->_mobileVal);
            $this->_getDesignPackage()->setTheme($this->_helper()->getMobileTheme());
        }

        if (version_compare(Mage::getVersion(), '1.9.1.0', '>=')) {
            $this->_saveOption('awmobile/compatibility/is_1910', 1);
            $this->_saveOption('awmobile/compatibility/is_not_1910', 0);
        } else {
            $this->_saveOption('awmobile/compatibility/is_1910', 0);
            $this->_saveOption('awmobile/compatibility/is_not_1910', 1);
        }

        Varien_Profiler::stop('aw::mobile::init_mobile');
        return $this;
    }

    /**
     * Check initiallized mobile theme
     * @return boolean
     */
    protected function _checkMobile()
    {
        $result = true;
        foreach ($this->_initParams as $type) {
            if (Mage::app()->getStore()->getConfig('design/theme/' . $type) != $this->_mobileVal) {
                $result = false;
            }
        }
        return $result;
    }

    protected function _registerMobilePageConfig()
    {
        if (!Mage::registry('_singleton/page/config')) {
            Mage::register('_singleton/page/config', Mage::getModel('awmobile/page_config'));
        }
    }

    public function predispatch($event)
    {       
        if (Mage::getConfig()->getNode('modules/Enterprise_Enterprise')) {
            if ($this->_helper()->getTargetPlatform() == self::TARGET_MOBILE) {
                $customDesign = Mage::getStoreConfig(AW_Mobile_Helper_Data::XML_PATH_MOBILE_CUSTOM_THEME);
                if (!$customDesign) {
                    $packageName = Mage::helper('awmobile')->getMobilePackage();
                    $themeName = Mage::helper('awmobile')->getMobileTheme();
                } else {
                    $customDesign = explode('/', $customDesign);
                    $packageName = $customDesign[0];
                    $themeName = $customDesign[1];
                }
            } else {
                Mage::getDesign()->setPackageName('aw_iphone_force_check');
                $packageName = Mage::getDesign()->getPackageName();
                $themeName = Mage::getDesign()->getTheme('');
            }

            $rewriteCookie = "{$packageName}_{$themeName}"; 
            $this->getCookie()->delete(self::MOBILE_COOKIE);            
            $this->getCookie()->set(self::MOBILE_COOKIE, $rewriteCookie, true, '/');           
      
        }  


        if (Mage::getDesign()->getArea() == 'adminhtml') {
            return;
        }


        if ($this->_helper()->getTargetPlatform() == self::TARGET_MOBILE) {
            $this->_registerMobilePageConfig();
            $this->_initMobile();
        }
    }

    public function getCookie()
    {
        return Mage::getSingleton('core/cookie');
    }

    protected function _saveOption($path, $value)
    {
        if (is_null(Mage::getStoreConfig($path)) || (Mage::getStoreConfig($path) !== $value)) {
            Mage::app()->getConfig()->saveConfig($path, $value);
        }
    }

    public function beforeFrontendInit($event)
    {
        Varien_Profiler::start('aw::mobile::frontend_init');
        if ($this->_helper()->isPageCache()) {
            $tags = Mage::app()->useCache();
            $tags['full_page'] = 0;
            Mage::app()->saveUseCache($tags);
        }

        if (Mage::getStoreConfig(self::XML_PATH_MERGE_JS)) {
            if (version_compare(Mage::getVersion(), '1.3.3.0', '<=')) {
                Mage::app()->getConfig()->saveConfig(self::XML_PATH_MERGE_JS, 0);
            }
        }
        Varien_Profiler::stop('aw::mobile::frontend_init');
    }

}