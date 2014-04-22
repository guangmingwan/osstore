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


class AW_Mobile_Model_Container_Links  extends Enterprise_PageCache_Model_Container_Abstract
{
    protected function _getIdentifier()
    {
        return $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '');
    }

    protected function _getCacheId()
    {
        return 'AWMOBILE_TOP_LINKS' . md5($this->_placeholder->getAttribute('cache_id') . $this->_getIdentifier());
    }

    protected function _renderBlock()
    {
        $template = $this->_placeholder->getAttribute('template');

        $links = new Mage_Page_Block_Template_Links();
        $links->setTemplate($template);

        $links->addLink(
            Mage::helper('awmobile')->__('Search'),
            '#',
            Mage::helper('awmobile')->__('Search'),
            false,
            array(),
            45,
            '',
            'class="button grey search" onclick="showSearchForm(1); return false;"'
        );
        $links->addLink(
            $this->getCartButtonText(),
            '#',
            $this->getCartButtonText(),
            false,
            array(),
            50,
            'id="gotocart-button-container"',
            'class="button red right" onclick="goToCart(); return false;"'
        );

        $links->setLayout(Mage::app()->getLayout());

        return $links->toHtml();
    }

    protected function _saveCache($data, $id, $tags = array(), $lifetime = null)
    {
        return false;
    }

    /**
     * retrives add to cart button text
     * @return string
     */
    public function getCartButtonText()
    {
        $count = Mage::helper('checkout/cart')->getSummaryCount();

        if( $count > 0 ) {
            $text = Mage::helper('awmobile')->__('My Cart (%s)', $count);
        } else {
            $text = Mage::helper('awmobile')->__('My Cart');
        }
        return $text;
    }

}