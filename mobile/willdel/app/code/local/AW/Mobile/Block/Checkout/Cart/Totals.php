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

class AW_Mobile_Block_Checkout_Cart_Totals extends Mage_Checkout_Block_Cart_Totals
{    
    /**
     * Retrives cart totals
     * @return array
     */
    public function getTotals() 
    {        
        if (Mage::helper('awmobile')->checkVersion('1.4.1.1')){
            if (!$this->_totals){
                $this->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
                $this->_totals = $this->getQuote()->getTotals();                                      
            }
            return $this->_totals;
        } else {
            return parent::getTotals();
        }                       
    }       
    
    protected function _getTotalRenderer($code)
    {
        $result = null;
        try {                     
            $result = parent::_getTotalRenderer($code);
        } catch (Exception $e){}
            
        if ($result){
            return $result;
        } else {
            return new Mage_Core_Block_Template();
        }                
    }    
}