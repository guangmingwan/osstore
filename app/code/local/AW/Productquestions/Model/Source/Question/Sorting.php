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
 * @package    AW_Productquestions
 * @version    1.4.5
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Productquestions_Model_Source_Question_Sorting {
    const BY_DATE = 1;
    const BY_HELPFULLNESS = 2;

    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    public static function toOptionArray() {
        $res = array();

        foreach (self::toShortOptionArray() as $key => $value)
            $res[] = array('value' => $key, 'label' => $value);

        return $res;
    }

    /*
     * Returns options array for sorting fields
     * @return array sorting_type => sorting_title
     */

    public static function toShortOptionArray() {
        return array(
            self::BY_DATE => Mage::helper('productquestions')->__('Date'),
            self::BY_HELPFULLNESS => Mage::helper('productquestions')->__('Helpfulness'),
        );
    }

    /*
     * Returns title of current sorting direction
     * @param int $dir Sorting direction
     * @return string Name of the direction
     */

    public static function getSortDirDescription($dir) {
        $helper = Mage::helper('productquestions');
        switch ($dir) {
            case self::SORT_ASC: return $helper->__('Ascending');
                break;
            case self::SORT_DESC: return $helper->__('Descending');
                break;
        }
        return 'Unknown';
    }

}
