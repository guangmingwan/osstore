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

class AW_Collpur_Model_Mysql4_Dealpurchases extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('collpur/dealpurchases', 'id');
    }

    public function loadPurchaseWithoutCoupon($dealPurchase, $dealId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('dealpurchases'))
            ->where('deal_id = ?', $dealId)
            ->where('qty_purchased > qty_with_coupons');
        if ($data = $this->_getReadAdapter()->fetchRow($select)) {
            $dealPurchase->addData($data);
        }
        $this->_afterLoad($dealPurchase);
        return $this;
    }

    public function loadByOrderItemId($dealPurchase, $orderItemId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('dealpurchases'))
            ->where('order_item_id = ?', $orderItemId);
        if ($data = $this->_getReadAdapter()->fetchRow($select)) {
            $dealPurchase->addData($data);
        }
        $this->_afterLoad($dealPurchase);
        return $this;
    }

}