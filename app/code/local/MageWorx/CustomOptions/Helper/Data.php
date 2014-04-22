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

class MageWorx_CustomOptions_Helper_Data extends Mage_Core_Helper_Abstract {
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 2;

    const XML_PATH_CUSTOMOPTIONS_ENABLED = 'mageworx_catalog/customoptions/enabled';
    const XML_PATH_CUSTOMOPTIONS_DEPENDENT_ENABLED = 'mageworx_catalog/customoptions/dependent_enabled';
    const XML_PATH_CUSTOMOPTIONS_HIDE_DEPENDENT_OPTION = 'mageworx_catalog/customoptions/hide_dependent_option';    
    const XML_PATH_CUSTOMOPTIONS_INVENTORY_ENABLED = 'mageworx_catalog/customoptions/inventory_enabled';
    const XML_PATH_CUSTOMOPTIONS_ENABLE_QNTY_INPUT = 'mageworx_catalog/customoptions/enable_qnty_input';
    const XML_PATH_CUSTOMOPTIONS_DISPLAY_QTY_FOR_OPTIONS = 'mageworx_catalog/customoptions/display_qty_for_options';
    const XML_PATH_CUSTOMOPTIONS_HIDE_OUT_OF_STOCK_OPTIONS = 'mageworx_catalog/customoptions/hide_out_of_stock_options';
    const XML_PATH_CUSTOMOPTIONS_IMAGES_ABOVE_OPTIONS = 'mageworx_catalog/customoptions/images_above_options';
    const XML_PATH_CUSTOMOPTIONS_ENABLE_CUSTOMER_GROUPS = 'mageworx_catalog/customoptions/enable_customer_groups';
    
    public function isEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_ENABLED);
    }
    
    public function isDependentEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_DEPENDENT_ENABLED);
    }
    
    public function hideDependentOption() {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_HIDE_DEPENDENT_OPTION);
    }
    
    public function isAbsolutePricesEnabled() {
        return Mage::getStoreConfig('mageworx_catalog/customoptions/absolute_prices_enabled');
    }
    
    public function isInventoryEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_INVENTORY_ENABLED);
    }
    
    public function isQntyInputEnabled() {        
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_ENABLE_QNTY_INPUT);
    }
    
    public function getDefaultOptionQtyLabel() {        
        return Mage::getStoreConfig('mageworx_catalog/customoptions/default_option_qty_label');
    }

    public function canDisplayQtyForOptions() {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_DISPLAY_QTY_FOR_OPTIONS);
    }
    
    public function canShowQtyPerOptionInCart() {
        return Mage::getStoreConfig('mageworx_catalog/customoptions/show_qty_per_option_in_cart');
    }
    

    public function canHideOutOfStockOptions() {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMOPTIONS_HIDE_OUT_OF_STOCK_OPTIONS);
    }
    
    public function getImagesThumbnailsSize() {        
        return intval(Mage::getStoreConfig('mageworx_catalog/customoptions/images_thumbnails_size'));
    }

    public function isImagesAboveOptions() {        
        return Mage::getStoreConfigFlag(self::XML_PATH_CUSTOMOPTIONS_IMAGES_ABOVE_OPTIONS);
    }
    
    public function isCustomerGroupsEnabled() {
        return Mage::getStoreConfigFlag(self::XML_PATH_CUSTOMOPTIONS_ENABLE_CUSTOMER_GROUPS);  
    }

    public function getOptionStatusArray() {
        return array(
            self::STATUS_VISIBLE => $this->__('Active'),
            self::STATUS_HIDDEN => $this->__('Disabled'),
        );
    }

    public function isEnterprise() {
        $res = false;
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')) {
            $res = true;
        }
        return $res;
    }

    public function getFilter($data) {
        $result = array();
        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_StringTrim());

        if ($data) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = $this->getFilter($value);
                } else {
                    $result[$key] = $filter->filter($value);
                }
            }
        }
        return $result;
    }

    public function getFiles($path) {
        return @glob($path . "*.*");
    }

    public function isCustomOptionsFile($groupId, $optionId, $valueId = false) {
        return $this->getFiles($this->getCustomOptionsPath($groupId, $optionId, $valueId));
    }

    public function getCustomOptionsPath($groupId, $optionId = false, $valueId = false) {
        return Mage::getBaseDir('media') . DS . 'customoptions' . DS . ($groupId ? $groupId : 'options') . DS . ($optionId ? $optionId . DS : '') . ($valueId ? $valueId . DS : '');
    }    

    public function deleteValueFile($groupId, $optionId, $valueId = false) {
        $dir = $this->getCustomOptionsPath($groupId, $optionId, $valueId);        
        $this->deleteFolder($dir);                
//        $files = $this->getFiles($dir);
//        
//        
//        if ($files) {
//            foreach ($files as $value) {
//                @unlink($value);
//                $this->deleteFolder($dir . $this->getImagesThumbnailsSize(). 'x' . DS);                
//            }
//        }
    }

    public function getFileName($filePath) {
        $name = '';
        $name = substr(strrchr($filePath, '/'), 1);
        if (!$name) {
            $name = substr(strrchr($filePath, '\\'), 1);
        }
        return $name;
    }

    public function getImgHtml($imagePath, $optionId, $optionTypeId = false, $isOneImg = false, $isArr = false) {
        
        if (!$imagePath) return '';        
        if (substr($imagePath, -1, 1)!=DS) $imagePath .= DS;
        
        $path = Mage::getBaseDir('media') . DS . 'customoptions' . DS . $imagePath;
        
        
        $file = @glob($path . "*.*");
        if (!$file) return '';
        $filePath = $file[0];
        $fileName = str_replace($path, '', $filePath);
        
        
        $imagesThumbnailsSize = $this->getImagesThumbnailsSize();
        
        $smallPath = $path . $imagesThumbnailsSize . 'x' . DS;
        
        $smallFilePath = $smallPath . $fileName;
        
        if (!file_exists($smallFilePath)) $this->getSmallImageFile($filePath, $smallPath, $fileName);
        
        $urlImagePath = trim(str_replace(DS, '/', $imagePath), ' /');
        
        $imgUrl = Mage::getBaseUrl('media') . 'customoptions/'. $urlImagePath. '/' . $imagesThumbnailsSize . 'x/' . $fileName;
        $bigImgUrl = Mage::getBaseUrl('media') . 'customoptions/'. $urlImagePath. '/' . $fileName;
        if (Mage::app()->getStore()->isAdmin() && Mage::app()->getRequest()->getControllerName()!='sales_order_create') {
            $impOption = array(
                'label' => $this->__('Delete Image'),
                'url' => $imgUrl,
                'id' => $optionId,
                'value_id' => $optionTypeId
            ); 
        } else {
            $impOption = array(
                'big_img_url' => $bigImgUrl,                
                'url' => $imgUrl,
                'value' => '',
                'is_one_img' => $isOneImg,
                'hide' => ($optionTypeId?true:false),
                'option_id' => $optionId,
                'option_type_id' => $optionTypeId
            );
        }    
        
        if ($isArr) return $impOption;
        
        $template = 'customoptions/option_image.phtml';
        if (Mage::app()->getRequest()->getControllerName()=='sales_order_create') $template = 'customoptions/composite/option_image.phtml';        
                
        return Mage::app()->getLayout()
                ->createBlock('core/template')
                ->setTemplate($template)
                ->addData(array('items' => new Varien_Object($impOption)))
                ->toHtml();
         
        
    }
    
    public function getSmallImageFile($fileOrig, $smallPath, $newFileName) {        
        try {
            $image = new Varien_Image($fileOrig);
            $origHeight = $image->getOriginalHeight();
            $origWidth = $image->getOriginalWidth();

            // settings
            $image->keepAspectRatio(true);
            $image->keepFrame(true);
            $image->keepTransparency(true);
            $image->constrainOnly(false);
            $image->backgroundColor(array(255, 255, 255));
            $image->quality(90);


            $width = null;
            $height = null;
            if (Mage::app()->getStore()->isAdmin()) {
                if ($origHeight > $origWidth) {
                    $height = $this->getImagesThumbnailsSize();
                } else {
                    $width = $this->getImagesThumbnailsSize();
                }
            } else {
                $configWidth = $this->getImagesThumbnailsSize();
                $configHeight = $this->getImagesThumbnailsSize();

                if ($origHeight > $origWidth) {
                    $height = $configHeight;
                } else {
                    $width = $configWidth;
                }
            }


            $image->resize($width, $height);

            $image->constrainOnly(true);
            $image->keepAspectRatio(true);
            $image->keepFrame(false);
            //$image->display();
            $image->save($smallPath, $newFileName);
        } catch (Exception $e) {
            
        }
    }

    public function getOptionImgView($option) {
        $block = Mage::app()->getLayout()
                ->createBlock('core/template')
                ->setTemplate('customoptions/option_image.phtml')
                ->addData(array('items' => $option))
                ->toHtml();

        return $block;
    }

    public function copyFolder($path, $dest) {
        if (is_dir($path)) {
            @mkdir($dest);
            $objects = scandir($path);
            if (sizeof($objects) > 0) {
                foreach ($objects as $file) {
                    if ($file == "." || $file == "..")
                        continue;
                    // go on
                    if (is_dir($path . DS . $file)) {
                        $this->copyFolder($path . DS . $file, $dest . DS . $file);
                    } else {
                        copy($path . DS . $file, $dest . DS . $file);
                    }
                }
            }
            return true;
        } elseif (is_file($path)) {
            return copy($path, $dest);
        } else {
            return false;
        }
    }

    public function deleteFolder($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . DS . $object) == "dir") {
                        $this->deleteFolder($dir . DS . $object);
                    } else {
                        unlink($dir . DS . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    
    public function getMaxOptionId() {
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $select = $connection->select()->from($tablePrefix . 'catalog_product_option', 'MAX(`option_id`)');        
        return intval($connection->fetchOne($select));
    }
    
    public function currencyByStore($price, $store, $format=true, $includeContainer=true) {
        if (version_compare(Mage::getVersion(), '1.5.0', '>=')) {
            return Mage::helper('core')->currencyByStore($price, $store, $format, $includeContainer);
        } else {
            return Mage::helper('core')->currency($price, $format, $includeContainer);
        }
    }
    
    // remove first plus
    public function getFormatedOptionPrice($priceStr) {
        if ($this->isAbsolutePricesEnabled()) $priceStr = str_replace('+', '', $priceStr);
        return $priceStr;
    }
    
    // translate and QuoteEscape
    public function __js($str) {
        return $this->jsQuoteEscape(str_replace("\'", "'", $this->__($str)));
    }    
}
