<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-ENTERPRISE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento ENTERPRISE edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento ENTERPRISE edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Followupemail
 * @version    3.4.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-ENTERPRISE.txt
 */


class AW_Followupemail_Model_Coupons extends Mage_SalesRule_Model_Coupon {
    public function fueLoadByRules($fueRule) {
        $prefix = $fueRule->getCouponPrefix();
        $expires = date('Y-m-d', strtotime('+'.((int)$fueRule->getCouponExpireDays()).' day', time()));
        $coupons = $this->getCollection();
        $coupons->addRuleToFilter($fueRule->getCouponSalesRuleId());
        $coupons->getSelect()
            ->where("code LIKE ?", $prefix.dechex($fueRule->getId()).'X%')
            ->where("expiration_date = ?", $expires);

        foreach($coupons as $coupon) {
            return $coupon;
        }
        return null;
    }

    public function fueLoadByCode($code) {
        $this->load($code, 'code');
        return $this;
    }
}
