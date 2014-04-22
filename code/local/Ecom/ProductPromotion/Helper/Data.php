<?php
	class Ecom_ProductPromotion_Helper_Data extends Mage_Core_Helper_Abstract {
		public function createBlock($block)  {
        $error = Mage::helper('core')->__('Invalid block type: %s', $block);
        if (is_string($block)) {
            if (strpos($block, '/') !== false) {
                if (!$block = Mage::getConfig()->getBlockClassName($block)) {
                    Mage::throwException($error);
                }
            }
            $fileName = mageFindClassFile($block);
            if ($fileName!==false) {
                include_once ($fileName);
                $block = new $block(array());
            }
        }
        if (!$block instanceof Mage_Core_Block_Abstract) {
            Mage::throwException($error);
        }
        return $block;
      }
	}