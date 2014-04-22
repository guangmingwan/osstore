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


class AW_Mobile_Model_Container_Cart  extends Enterprise_PageCache_Model_Container_Abstract
{
    protected function _getIdentifier()
    {
        return $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '');
    }

    protected function _getCacheId()
    {
        return 'AWMOBILE_CART' . md5($this->_placeholder->getAttribute('cache_id') . $this->_getIdentifier());
    }

    protected function _renderBlock()
    {
        $layout = Mage::getSingleton('core/layout');
        $cart = $layout->getBlock('checkout.cart');
        if (!$cart) {
            $layout->getUpdate()->addUpdate('
                <block type="checkout/cart" name="checkout.cart">
                    <action method="setCartTemplate"><value>checkout/cart.phtml</value></action>
                    <action method="setEmptyTemplate"><value>checkout/cart/noItems.phtml</value></action>
                    <action method="chooseTemplate"/>
                    <action method="addItemRender"><type>bundle</type><block>bundle/checkout_cart_item_renderer</block><template>checkout/cart/item/default.phtml</template></action>
                    <action method="addItemRender"><type>grouped</type><block>checkout/cart_item_renderer_grouped</block><template>checkout/cart/item/default.phtml</template></action>
                    <action method="addItemRender"><type>configurable</type><block>checkout/cart_item_renderer_configurable</block><template>checkout/cart/item/default.phtml</template></action>
                    <action method="addItemRender"><type>downloadable</type><block>downloadable/checkout_cart_item_renderer</block><template>downloadable/checkout/cart/item/default.phtml</template></action>

                    <block type="checkout/multishipping_link" name="checkout.cart.methods.multishipping" as="multishipping_link" template="checkout/multishipping/link.phtml"/>
                    <block type="core/text_list" name="checkout.cart.top_methods" as="top_methods">
                        <block type="checkout/onepage_link" name="checkout.cart.methods.onepage" template="checkout/onepage/link.phtml"/>
                    </block>

                    <block type="core/text_list" name="checkout.cart.methods" as="methods" />

                    <block type="awmobile/checkout_cart_totals" name="checkout.cart.totals" as="totals" template="checkout/cart/totals.phtml"/>

                    <block type="checkout/cart_coupon" name="checkout.cart.coupon" as="coupon" template="checkout/cart/coupon.phtml"/>
                </block>

                <reference name="checkout.cart">
                    <action method="addItemRender">
                        <type>giftcard</type>
                        <block>enterprise_giftcard/checkout_cart_item_renderer</block>
                        <template>checkout/cart/item/default.phtml</template>
                    </action>
                </reference>

                <reference name="checkout.cart">
                    <block type="enterprise_giftcardaccount/checkout_cart_giftcardaccount" template="giftcardaccount/cart/block.phtml" name="checkout.cart.giftcardaccount" as="giftcards" />
                </reference>
            ');
            $layout->generateXml()->generateBlocks();
            $cart = $layout->getBlock('checkout.cart');
        }

        return $cart->toHtml();
    }

    protected function _saveCache($data, $id, $tags = array(), $lifetime = null)
    {
        return false;
    }
}