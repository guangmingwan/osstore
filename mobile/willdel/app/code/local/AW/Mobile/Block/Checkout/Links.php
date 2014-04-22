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

class AW_Mobile_Block_Checkout_Links extends Mage_Checkout_Block_Links
{
    /**
     * Add shopping cart link to parent block
     *
     * @return Mage_Checkout_Block_Links
     */
    public function addCartLink()
    {
        if ($parentBlock = $this->getParentBlock()) {
            $parentBlock->addLink($this->getCartButtonText(), '#', $this->getCartButtonText(), false, array(), 50, 'id="gotocart-button-container"', 'class="button red right" onclick="goToCart(); return false;"');
        }
        return $this;
    }

    /**
     * retrives add to cart button text
     * @return string
     */
    public function getCartButtonText()
    {
        $count = $this->helper('checkout/cart')->getSummaryCount();

        if( $count > 0 ) {
            $text = $this->__('My Cart (%s)', $count);
        } else {
            $text = $this->__('My Cart');
        }
        return $text;
    }

    /**
     * Retrives Link Html
     * @return string
     */
    public function getLinkHtml()
    {
        $bText = $this->getCartButtonText();
        return '<a href="#" title="'.$bText.'" class="button red right" onclick="goToCart(); return false;">'.$bText.'</a>';
    }

}
