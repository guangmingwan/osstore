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


class AW_Collpur_Block_Adminhtml_Deal_Edit_Tab_CouponsNotActive extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $deal = Mage::registry('collpur_deal');

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('collpur')->__('Coupons')));
        $fieldset->addClass('coupons_not_active_fieldset');
        $form->setHtmlIdPrefix('deal_');

        $fieldset->addField('not_active_coupons_message', 'label', array(
            'name' => 'not_active_coupons_message',
            'title' => $this->__('Coupons functionality will be available after deal is created'),
            'label' => $this->__('Coupons functionality will be available after deal is created'),
            'class' => 'validate-new-password'
        ));


        $form->setValues($deal->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

}