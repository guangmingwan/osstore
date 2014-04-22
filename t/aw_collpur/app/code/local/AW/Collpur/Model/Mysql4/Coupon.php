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

class AW_Collpur_Model_Mysql4_Coupon extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('collpur/coupon', 'id');
    }

    public function isUnique($couponCode)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getTable('collpur/coupon'),array('coupon_code'))->where('coupon_code = ?', $couponCode);
        if ($this->_getReadAdapter()->fetchOne($select)) { return false; }
        return true;
    }
    
    public function loadFreeCoupon($coupon, $dealId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('coupon'))
            ->where('deal_id = ?', $dealId)
            ->where('status = ?', AW_Collpur_Model_Coupon::STATUS_PENDING);
        if ($data = $this->_getReadAdapter()->fetchRow($select)) {
            $coupon->addData($data);
        }
        $this->_afterLoad($coupon);
        return $this;
    }

}