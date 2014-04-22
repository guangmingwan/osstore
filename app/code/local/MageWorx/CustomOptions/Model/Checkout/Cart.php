<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomOptions
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Advanced Product Options extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomOptions
 * @author     MageWorx Dev Team
 */

class MageWorx_CustomOptions_Model_Checkout_Cart extends Mage_Checkout_Model_Cart {

    public function updateItems($data) {
        Mage::dispatchEvent('checkout_cart_update_items_before', array('cart' => $this, 'info' => $data));

        /* @var $messageFactory Mage_Core_Model_Message */
        $messageFactory = Mage::getSingleton('core/message');
        $session = $this->getCheckoutSession();
        $qtyRecalculatedFlag = false;
        
        foreach ($data as $itemId => $itemInfo) {
            $item = $this->getQuote()->getItemById($itemId);
            if (!$item) {
                continue;
            }

            if (!empty($itemInfo['remove']) || (isset($itemInfo['qty']) && $itemInfo['qty'] == '0')) {
                $this->removeItem($itemId);
                continue;
            }
                      

            $qty = isset($itemInfo['qty']) ? (float) $itemInfo['qty'] : false;                       
            
            // outOfStock
            if ($qty > 0) {
                $outOfStock = false;
                if (Mage::helper('customoptions')->isInventoryEnabled()) {                    
                    foreach ($item->getOptions() as $option) {
                        $post = @unserialize($option->getValue());
                        if ($post!==false && isset($post['options'])) {                            
                            foreach ($post['options'] as $optionId => $values) {
                                $productOption = Mage::getModel('catalog/product_option')->load($optionId);
                                if (!is_array($values) && ($productOption->getType()=='drop_down' || $productOption->getType()=='radio')) $values = array($values);                                
                                foreach ($values as $optionTypeId) {                                        
                                    $row = $productOption->getOptionValue($optionTypeId);                                        
                                    if (isset($row['customoptions_qty']) && substr($row['customoptions_qty'], 0, 1)!='x' && $row['customoptions_qty']!=='') {                                            
                                        switch ($productOption->getType()) {
                                            case 'checkbox':                            
                                                if (isset($post['options_'.$optionId.'_'.$optionTypeId.'_qty'])) $optionQty = intval($post['options_'.$optionId.'_'.$optionTypeId.'_qty']); else $optionQty = 1;
                                                break;
                                            case 'drop_down':
                                            case 'radio':                                                    
                                                if (isset($post['options_'.$optionId.'_qty'])) $optionQty = intval($post['options_'.$optionId.'_qty']); else $optionQty = 1;
                                                break;
                                            case 'multiple':
                                                $optionQty = 1;                            
                                                break;                       
                                        }                        
                                        $optionTotalQty = ($productOption->getCustomoptionsIsOnetime()?$optionQty:$optionQty*$qty);                        
                                        if ($row['customoptions_qty']<$optionTotalQty) {
                                            $outOfStock = true;
                                            break; break; break;
                                        }                            
                                    }
                                }                                
                            }
                        }
                    }
                }

                if ($outOfStock) {
                    $session->addError(Mage::helper('cataloginventory')->__('The requested quantity for "%s" is not available.', $item->getProduct()->getName()));
                } else {
                    $item->setQty($qty);

                    if (isset($itemInfo['before_suggest_qty']) && ($itemInfo['before_suggest_qty'] != $qty)) {
                        $qtyRecalculatedFlag = true;
                        $message = $messageFactory->notice(Mage::helper('checkout')->__('Quantity was recalculated from %d to %d', $itemInfo['before_suggest_qty'], $qty));
                        $session->addQuoteItemMessage($item->getId(), $message);
                    }
                }
            }                        
            
        }
        
        
        
        

        if ($qtyRecalculatedFlag) {
            $session->addNotice(
                    Mage::helper('checkout')->__('Some products quantities were recalculated because of quantity increment mismatch')
            );
        }

        Mage::dispatchEvent('checkout_cart_update_items_after', array('cart' => $this, 'info' => $data));
        return $this;
    }

}
