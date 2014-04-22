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


class AW_Collpur_DealsController extends Mage_Core_Controller_Front_Action {

    public function process_listAction() {

        $section = (string) @strip_tags(Mage::app()->getRequest()->getParam('section'));
        if (AW_Collpur_Model_Source_Menus::isNotAllowed($section)) {
            $this->norouteAction();
            return;
        }
        
        if (!$section || $section == AW_Collpur_Helper_Deals::FEATURED) {
            $startPage = AW_Collpur_Model_Source_Menus::getFirstAvailable();
            if ($startPage == AW_Collpur_Helper_Deals::FEATURED) {
                $this->getResponse()->setRedirect(Mage::getUrl('deals/deals/view', array('_store' => Mage::app()->getStore()->getId(), 'id' => Mage::getModel('collpur/deal')->getRandomFeaturedId(), 'mode' => AW_Collpur_Helper_Deals::FEATURED)));
            } else {
                $this->getResponse()->setRedirect(Mage::getUrl('deals/deals/list', array('_store' => Mage::app()->getStore()->getId(), 'section' => $startPage)));
            }
        } else {
            $this->loadLayout();

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('tag/session');
            $this->_initLayoutMessages('checkout/session');

            $layout = $this->getLayout();
            $layout->getBlock('content')->append(
                    $layout->createBlock('collpur/deals')
            );
            $this->renderLayout();
        }
    }

    public function __call($method, $params) {
        AW_Collpur_Helper_ProcessValidator::initProcess($this, $method);
    }

    public function process_viewAction() {

        $this->loadLayout();

        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('tag/session');
        $this->_initLayoutMessages('checkout/session');

        $this->renderLayout();
    }

    public function process_emptyAction() {
        
        $this->loadLayout(); 
        $this->renderLayout();        

    }

}