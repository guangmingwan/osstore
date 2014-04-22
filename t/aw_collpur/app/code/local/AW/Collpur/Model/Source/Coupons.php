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


class AW_Collpur_Model_Source_Coupons {

    public static function toOptionArray() {

        return array(
            AW_Collpur_Model_Coupon::STATUS_PENDING => Mage::helper('collpur')->__('Pending'),           
            AW_Collpur_Model_Coupon::STATUS_NOT_USED => Mage::helper('collpur')->__('Not used'),
            AW_Collpur_Model_Coupon::STATUS_USED => Mage::helper('collpur')->__('Used'),
            AW_Collpur_Model_Coupon::STATUS_EXPIRED => Mage::helper('collpur')->__('Expired')           
        );
    }

}