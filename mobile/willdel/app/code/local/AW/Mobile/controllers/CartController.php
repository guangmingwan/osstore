<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
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
 * @package    AW_Mobile
 * @version    1.6.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

/**
 * aheadWorks Mobile Cart Controller
 */
require_once 'Mage/Checkout/controllers/CartController.php';
class AW_Mobile_CartController extends Mage_Checkout_CartController
{
    /**
     * Response array via JSON
     * @param array $result
     */
    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()->setBody(Zend_Json::encode($result));
        return;
    }

    /**
     * Helper
     * @return AW_Mobile_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('awmobile');
    }

    /**
     * Retrives actual cart content
     * @return string
     */
    protected function _getCartContent()
    {
        try {
            $layout = Mage::app()->getLayout();
            $update = $layout->getUpdate()->addHandle('checkout_cart_index')->addHandle('default')->load();
            $layout->generateXml()->generateBlocks($layout->getNode('cart.content'));
            $layout->getMessagesBlock()->addMessages($this->_getSession()->getMessages(true));
            $body = $layout->getBlock('cart.content')->toHtml();
        } catch (Exception $e) {
            $body = $layout->getBlock('cart.content')->toHtml();
        }
        return $body;
    }

    /**
     * Fill response body by cart content
     * Required for Ajax response
     * @return AW_Mobile_CartController
     */
    public function contentAction()
    {
        $this->getResponse()->setBody($this->_getCartContent());
    }

    /**
     * Is add action
     * @return boolen
     */
    protected function _isAddAction()
    {
        return ($this->getRequest()->getActionName() === 'add');
    }

    /**
     * Fill response body by cart content
     * Required for Ajax response
     * @return AW_Mobile_CartController
     */
    protected function _goBack()
    {
        if (($quote = $this->_getCart()->getQuote()) && $this->_isAddAction()) {
            foreach ($quote->getAllItems() as $item) {
                if (!$item->getPrice() && !$item->getHasError()) {
                    $item->setPrice($item->getProduct()->getFinalPrice())
                        ->calcRowTotal()
                        ->save();
                }
            }
        }

        $block = new AW_Mobile_Block_Checkout_Links();
        $linkHtml = $block->getLinkHtml();

        $result = array(
            'cart_content' => $this->_getCartContent(),
            'link_content' => $linkHtml,
        );

        $this->_ajaxResponse($result);
        return $this;
    }

    /**
     * Prepare messages to show in ajax cart of Mobile Theme
     *
     * @return AW_Mobile_CartController
     */
    protected function _prepareMessages()
    {
        return $this;
    }

    protected function _redirectReferer($defaultUrl = null)
    {
        if (Mage::registry('_no_redirect_flag')) {
            $this->_prepareMessages()
                ->_goBack();
        } else {
            parent::_redirectReferer($defaultUrl);
        }
    }

    public function addAction()
    {
        Mage::register('_no_redirect_flag', true, true);
        parent::addAction();


        //        $this->getResponse()
        //                ->clearHeader('Location')
        //                ->setHttpResponseCode(200);

        # Version for 1.3.3.0
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $response->clearHeaders()->setHttpResponseCode(200);

        foreach ($headers as $header) {
            if (strtolower($header['name']) != 'location') {
                $response->setHeader($header['name'], $header['value'], $header['replace']);
            }
        }
        # end part        

        $this->_prepareMessages()
            ->_goBack();
    }

    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                    ->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
            }
        }
        $this->_goBack();
    }

    /**
     * Add Gift Card to current quote
     *
     */
    public function addGiftcardAction()
    {
        $data = $this->getRequest()->getPost();
        if (isset($data['giftcard_code'])) {
            $code = $data['giftcard_code'];
            try {
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->addToCart();
                Mage::getSingleton('checkout/session')->addSuccess(
                    $this->__('Gift Card "%s" was added.', Mage::helper('core')->htmlEscape($code))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));
                Mage::getSingleton('checkout/session')->addError(
                    $e->getMessage()
                );
            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException($e, $this->__('Cannot apply gift card.'));
            }
        }
        $this->_goBack();
    }

    /**
     * Remove Gift Card to current quote
     *
     */
    public function removeGiftcardAction()
    {
        if ($code = $this->getRequest()->getParam('code')) {
            try {
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->removeFromCart();
                Mage::getSingleton('checkout/session')->addSuccess(
                    $this->__('Gift Card "%s" was removed.', Mage::helper('core')->htmlEscape($code))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('checkout/session')->addError(
                    $e->getMessage()
                );
            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException($e, $this->__('Cannot remove gift card.'));
            }
        }
        $this->_goBack();
    }

    /**
     * Remove Store Credit from current quote
     *
     */
    public function removeCustomerbalanceAction()
    {
        if (!Mage::helper('enterprise_customerbalance')->isEnabled()) {
            $this->_goBack();
            return;
        }

        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if ($quote->getUseCustomerBalance()) {
            Mage::getSingleton('checkout/session')->addSuccess(
                $this->__('The store credit payment has been removed from shopping cart.')
            );
            $quote->setUseCustomerBalance(false)->collectTotals()->save();
        } else {
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Store Credit payment is not being used in your shopping cart.')
            );
        }

        $this->_goBack();
    }

}
