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


class AW_Followupemail_Model_Salesrule_Rule {
    public function toOptionArray() {
        $rulesCollection = Mage::getModel('salesrule/rule')->getResourceCollection()
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('coupon_type', Mage::helper('followupemail/coupon')->getFUECouponsCode());
        
        $result = array('' => 'Please, select a rule');
        foreach($rulesCollection as $rule)
            $result[$rule->getRuleId()] = $rule->getName();

        return $result;
    }
}
