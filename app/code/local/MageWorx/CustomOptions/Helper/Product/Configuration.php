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
class MageWorx_CustomOptions_Helper_Product_Configuration extends Mage_Catalog_Helper_Product_Configuration {
    
    public function getCustomOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item) {
        $this->setCustomOptionsDetails($item);
        return parent::getCustomOptions($item);
    }
    
    public function getActualProductPrice($product, $store) {
        $price = $product->getPrice();
        $specialPrice = $product->getSpecialPrice();        
        if (!is_null($specialPrice) && $specialPrice != false) {
            if (Mage::app()->getLocale()->isStoreDateInInterval($store, $product->getSpecialFromDate(), $product->getSpecialToDate())) {
                $price = min($price, $specialPrice);
            }
        }
        return $price;        
    }
    
    public function setCustomOptionsDetails($item) {
        
        if (!Mage::helper('customoptions')->canShowQtyPerOptionInCart()) return $this;
        
        $product = $item->getProduct();       
        $store = $product->getStore();
        $basePrice = $this->getActualProductPrice($product, $store);
        
        $optionIds = $item->getOptionByCode('option_ids');
        if ($optionIds) {
            foreach (explode(',', $optionIds->getValue()) as $optionId) {
                $option = $product->getOptionById($optionId);
                if ($option) {
                    $optionQty = null;
                    $qty = $item->getQty();
                    $quoteItemOptionInfoBuyRequest = unserialize($product->getCustomOption('info_buyRequest')->getValue());
                    switch ($option->getType()) {
                        case 'checkbox':
                            if (isset($quoteItemOptionInfoBuyRequest['options'][$optionId])) {                                                                
                                $optionValues = array();
                                foreach ($option->getValues() as $key=>$itemV) {                                    
                                    if (isset($quoteItemOptionInfoBuyRequest['options_'.$optionId.'_'.$itemV->getOptionTypeId().'_qty'])) $optionQty = intval($quoteItemOptionInfoBuyRequest['options_'.$optionId.'_'.$itemV->getOptionTypeId().'_qty']); else $optionQty = 1;
                                    if (!isset($quoteItemOptionInfoBuyRequest['options'][$optionId]) || in_array($itemV->getOptionTypeId(), $quoteItemOptionInfoBuyRequest['options'][$optionId])) {
                                        $optionTotalQty = ($option->getCustomoptionsIsOnetime()?$optionQty:$optionQty*$qty);
                                    	$price = (($itemV->getPriceType()=='percent')?$basePrice * $itemV->getPrice() / 100 : $itemV->getPrice());
                                        if ($itemV->getOrigTitle()) $itemV->setTitle($itemV->getOrigTitle()); else $itemV->setOrigTitle($itemV->getTitle());
                                    	$itemV->setTitle(($optionTotalQty>1?$optionTotalQty.' x ':'') . $itemV->getTitle() . ($price>0?' - '.Mage::helper('customoptions')->currencyByStore($price*$optionTotalQty, $store, true, false):''));
                                    }
                                    $optionValues[$key]=$itemV;
                                }
                                $option->setValues($optionValues);
                                break;                                
                            }
                            break;
                        case 'drop_down':
                        case 'radio':                            
                            if (isset($quoteItemOptionInfoBuyRequest['options_'.$optionId.'_qty'])) $optionQty = intval($quoteItemOptionInfoBuyRequest['options_'.$optionId.'_qty']); else $optionQty = 1;
                        case 'multiple':
                            if (!isset($optionQty)) $optionQty = 1;
                            $optionValues = array();                            
                            $optionTotalQty = ($option->getCustomoptionsIsOnetime()?$optionQty:$optionQty*$qty);
                            foreach ($option->getValues() as $key=>$itemV) {                                
                                if (!isset($quoteItemOptionInfoBuyRequest['options'][$optionId]) || $itemV->getOptionTypeId()==$quoteItemOptionInfoBuyRequest['options'][$optionId]) {
                                    $price = (($itemV->getPriceType()=='percent')?$basePrice * $itemV->getPrice() / 100 : $itemV->getPrice());                                
                                    if ($itemV->getOrigTitle()) $itemV->setTitle($itemV->getOrigTitle()); else $itemV->setOrigTitle($itemV->getTitle());
                                    $itemV->setTitle(($optionTotalQty>1?$optionTotalQty.' x ':'') . $itemV->getTitle() . ($price>0?' - '.Mage::helper('customoptions')->currencyByStore($price*$optionTotalQty, $store, true, false):''));                             
                                }
                                $optionValues[$key]=$itemV;
                            }
                            $option->setValues($optionValues);
                            break;
                        case 'field':
                        case 'area':
                        case 'file':
                        case 'date':
                        case 'date_time':
                        case 'time':
                            $optionTotalQty = ($option->getCustomoptionsIsOnetime()?1:$qty);
                            $price = (($option->getPriceType()=='percent')?$basePrice * $option->getPrice() / 100 : $option->getPrice());
                            
                            if ($option->getOrigTitle()) $option->setTitle($option->getOrigTitle()); else $option->setOrigTitle($option->getTitle());                            
                            $option->setTitle(($optionTotalQty>1?$optionTotalQty.' x ':'') . $option->getTitle() . ($price>0?' - '.Mage::helper('customoptions')->currencyByStore($price, $store, true, false):''));

                            break;
                    }
                }
            }
        }
    }
    
    
}
