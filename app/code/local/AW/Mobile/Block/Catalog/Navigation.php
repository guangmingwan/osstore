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
 * AW Mobile Navigation Menu
 */
class AW_Mobile_Block_Catalog_Navigation extends Mage_Catalog_Block_Navigation
{

    /**
     * Navigation Frames Path
     */
    const NAVIGATION_FRAMES = 'navigation_frames';

    /**
     * Is Navigation Page Path
     */
    const IS_NAVIGATION_PAGE = 'is_navigate';

    /**
     * Navigation frames data
     * @var array
     */
    protected $_frames = array();

    /**
     * Class constructor
     * @return AW_Mobile_Block_Catalog_Navigation
     */
    protected function _construct()
    {
        Mage::register(self::IS_NAVIGATION_PAGE, true);
        $return = parent::_construct();
    }

    protected function  _prepareLayout()
    {
        $this->_prepareFrames();
        parent::_prepareLayout();
    }

    /**
     * Retrives active categories count
     * @param Varien_Object| $object
     * @return integer
     */
    public function __getChildrenCount($object)
    {
        if (Mage::getStoreConfig('catalog/frontend/flat_catalog_category')) {
            if (!$object->getChildrenCount()) {
                $count = 0;
                if ($object->getChildrenNodes()) {
                    foreach ($object->getChildrenNodes() as $child) {
                        if ($child->getIsActive()) {
                            $count++;
                        }
                    }
                    return $count;
                }
                if ($object->getChildren()) {
                    foreach ($object->getChildren() as $child) {
                        if ($child->getIsActive()) {
                            $count++;
                        }
                    }
                    return $count;
                }
            }
            return $object->getChildrenCount();
        } else {
            if ($object->getChildrenCount()) {
                $count = 0;
                foreach ($object->getChildren() as $child) {
                    if ($child->getIsActive()) {
                        $count++;
                    }
                }
                return $count;
            }
        }
        return 0;
    }

    /**
     * Retrives frame object
     * @param Varien_Object $object Category Tree Node
     * @return Varien_Object
     */
    protected function _getFrame(Varien_Object $object, $level = 1)
    {
        $frame = new Varien_Object();
        $frame->setFrameId('category' . $object->getId());
        $frame->setFrameCategoryId($object->getId());
        $frame->setHeader($object->getName());
        $frame->setLevel($level + 1);
        if (Mage::getStoreConfig('catalog/frontend/flat_catalog_category')) {
            $frame->setChildren($object->getChildrenNodes());
        } else {
            $frame->setChildren($object->getChildren());
        }
        $frame->setChildrenCount($this->__getChildrenCount($object));

        if (Mage::getStoreConfig('catalog/frontend/flat_catalog_category')) {
            if (is_array($frame->getChildrenNodes()) || (is_object($frame->getChildrenNodes()) && (get_class($frame->getChildrenNodes()) == 'Varien_Data_Tree_Node_Collection'))) {
                foreach ($frame->getChildrenNodes() as $child) {
                    $this->_frames[] = $this->_getFrame($child, ($level + 1));
                }
            }
        } else {
            if (is_array($frame->getChildren()) || (is_object($frame->getChildren()) && (get_class($frame->getChildren()) == 'Varien_Data_Tree_Node_Collection'))) {
                foreach ($frame->getChildren() as $child) {
                    $this->_frames[] = $this->_getFrame($child, ($level + 1));
                }
            }
        }
        return $frame;
    }

    /**
     * Retrives is Prent Flag for category
     * @param Varien_Object $category
     * @return boolean
     */
    public function isParent($category)
    {
        if (!$category) {
            return false;
        }

        $max_depth = Mage::getStoreConfig('catalog/navigation/max_depth');

        if ($max_depth) {
            return (($this->__getChildrenCount($category) > 0) && ($category->getLevel() <= $max_depth));
        } else {
            return ($this->__getChildrenCount($category) > 0);
        }


        return true;
    }

    /**
     * Prepare navigation frames for Navigation page
     */
    protected function _prepareFrames()
    {
        if (count($this->getStoreCategories())) {
            foreach ($this->getStoreCategories() as $category) {
                $this->_frames[] = $this->_getFrame($category);
            }
        }
        Mage::register(self::NAVIGATION_FRAMES, $this->_frames);
    }

    /**
     * Retrives home page category Id
     * Required for history backup
     *
     * @return integer
     */
    public function getHomeId()
    {
        return $this->getCurrentCategory()->getId();
    }

    public function getDir($frame)
    {
        if (!isset($html)) $html = '';
        $html .= '<div class="header-nav" id="' . $frame->getFrameId() . '">';
        $html .= '<ul id="nav">';
        if ($frame->getChildren()) {
            foreach ($frame->getChildren() as $category) {
                $link = ($this->isParent($category) ? '#category' . $category->getId() : $this->getCategoryUrl($category));
                $html .= '<li class="arrow"><a href="' . $link . '"';

                if ($this->isParent($category)) {
                    $html .= 'class="to_child"';
                }

                $html .= '>' . $this->htmlEscape($category->getName()) . '</a>';
                $html .= '<script type="text/javascript">catPath[' . $category->getId() . '] = "' . $category->getPath() . '"; catUrl[' . $category->getId() . '] = "' . $this->getCategoryUrl($category) . '";</script>';
                $html .= '<li>';
            }
        }
        $html .= '<script type="text/javascript">$("' . $frame->getFrameId() . '").category_id = ' . $frame->getFrameCategoryId() . '</script></ul></div>';

        if ($frame->getChildren()) {
            foreach ($frame->getChildren() as $category_level) {
                if (is_object($category_level)) {
                    $html .= $this->getDir($this->_getFrame($category_level));
                }
            }
        }
        return $html;
    }
}
