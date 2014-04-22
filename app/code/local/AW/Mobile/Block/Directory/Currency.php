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
 * Currency Switcher
 */
class  AW_Mobile_Block_Directory_Currency extends Mage_Directory_Block_Currency
{
    /**
     * Helper
     * @return AW_Mobile_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('awmobile');
    }
    
    public function getSwitchCurrencyUrl($code) 
    {
        if (!$this->_helper()->checkVersion('1.4.0.0')){
            return $this->helper('directory/url')->getSwitchCurrencyUrl()."currency/".$code;
        } else {        
            return parent::getSwitchCurrencyUrl($code);
        }
    }
    
    
}