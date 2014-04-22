<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
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
 * @package    AW_Collpur
 * @version    1.0.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Collpur_Block_Customer_Basedeal extends Mage_Core_Block_Template {

    const UNPAID = 'Unpaid';
    const PAID =   'Paid';
    const REFUNDED = 'Refunded';

    protected $_awcpStoreModel;
    protected $_currencyHelper;

    protected $_coreDate;

    protected function _construct() {

        $this->_coreDate = Mage::getModel('core/date');
        $this->_awcpStoreModel = Mage::app()->getStore();
        $this->_currencyHelper = Mage::helper('core');
    }

    public function getLocalDate($gmtDate) {

        if (!(int) $gmtDate)
        return '--';
        return $this->_coreDate->date(NULL, $gmtDate);
    }

    public function getBackUrl() {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/');
    }

     public function getPurchaseStatus($orderItemId) {

        $orderItem = Mage::getModel('sales/order_item')->load($orderItemId); 
        if(!$orderItem->getId()) return 'STATUS UNKNOWN';
        if(!(int) $orderItem->getQtyInvoiced() || $orderItem->getQtyInvoiced() != $orderItem->getQtyOrdered()) return self::UNPAID;
        if((int) $orderItem->getQtyInvoiced() && $orderItem->getQtyInvoiced() == $orderItem->getQtyRefunded()) return self::REFUNDED;
        if($orderItem->getQtyInvoiced() == $orderItem->getQtyOrdered()) return self::PAID;

    }




 
}