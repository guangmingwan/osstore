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


class AW_Collpur_Helper_Adminhtml_Deals extends Mage_Core_Helper_Abstract {

    public function getConfig() {

        $config = array(
            'active' => array('gridFilter' => array('is_active' => 1), 'headerText' => 'Active deals'),
            'pending' => array('gridFilter' => array('progress' => AW_Collpur_Model_Source_Progress::PROGRESS_NOT_RUNNING), 'headerText' => 'Upcoming deals'),
            'succeed' => array('gridFilter' => array('is_success' => 1), 'headerText' => 'Successed deals'),
            'default' => array('gridFilter' => array(), 'headerText' => 'All deals')
        );

        return $config;
    }

    public function process($filter) {
        $param = Mage::app()->getRequest()->getParam('deal_filter');
        if (!$param)
            $param = 'default';
        $config = $this->getConfig();
        if (isset($config[$param])) {
            return $this->{$filter}($config[$param][$filter]);
        }
    }

    public function headerText($data=null) {
        return $this->__($data);
    }

    public function gridFilter($data=null) {
        return $data;
    }

}