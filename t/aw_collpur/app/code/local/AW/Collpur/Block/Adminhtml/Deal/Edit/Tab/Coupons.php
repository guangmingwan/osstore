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


class AW_Collpur_Block_Adminhtml_Deal_Edit_Tab_Coupons extends Mage_Adminhtml_Block_Widget_Form {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('aw_collpur/deal/edit/tab/coupons.phtml');
    }

    protected function _prepareForm() {
        $deal = Mage::registry('collpur_deal');

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('collpur')->__('Coupons')));
        $form->setHtmlIdPrefix('deal_');


        if (!$deal->getEnableCoupons()) {
            $this->setDisableGrid(true);
            $this->setDisableRow(true);
        }

        $fieldset->addField('enable_coupons', 'select', array(
            'label' => $this->__('Enable coupons'),
            'title' => $this->__('Enable coupons'),
            'name' => 'enable_coupons',
            'options' => array(
                '1' => $this->__('Yes'),
                '0' => $this->__('No'),
            ),
        ));


        $expire = $this->getLayout()->createBlock('collpur/adminhtml_deal_edit_tab_coupons_renderer_qty');
        $fieldset->addField('coupon_expire_after_days', 'text', array(
            'name' => 'coupon_expire_after_days',
            'label' => $this->__('Coupon expires after has been received, days'),
            'title' => $this->__('Coupon expires after has been received, days'),
            'after_element_html' => $this->__('Leave empty or set zero for unlimited'),
        ))->setDisableRow($this->getDisableRow())->setRenderer($expire);


        $couponPrefix = $this->getLayout()->createBlock('collpur/adminhtml_deal_edit_tab_coupons_renderer_prefix');
        $fieldset->addField('coupon_prefix', 'text', array(
            'name' => 'coupon_prefix',
            'label' => $this->__('Coupon prefix'),
            'title' => $this->__('Coupon prefix'),
            'after_element_html' => '',
        ))->setDisableRow($this->getDisableRow())->setRenderer($couponPrefix);

        $generator = $this->getLayout()->createBlock('collpur/adminhtml_deal_edit_tab_coupons_renderer_generator');
        $fieldset->addField('generator', 'note', array(
            'title' => $this->__('Generate coupons'),
            'label' => $this->__('Generate coupons'),
        ))->setDisableRow($this->getDisableRow())->setRenderer($generator);


        $form->setValues($deal->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getGridHtml() {
        return
                $this
                ->getLayout()
                ->createBlock('collpur/adminhtml_deal_coupon_grid')
                ->toHtml();
    }

}