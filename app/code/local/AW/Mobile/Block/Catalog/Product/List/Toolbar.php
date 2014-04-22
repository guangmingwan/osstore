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

class AW_Mobile_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    /**
     * Cached pager
     * @var Mage_Catalog_Block_Product_List_Toolbar_Pager
     */
    protected $_pager = null;

    /**
     * Retrives pager block
     * @return Mage_Catalog_Block_Product_List_Toolbar_Pager
     */
    protected function _getPager()
    {
        if (!$this->_pager){
            $pagerBlock = $this->getChild('product_list_toolbar_pager');

            if ($pagerBlock instanceof Varien_Object) {
                /* @var $pagerBlock Mage_Page_Block_Html_Pager */
                $pagerBlock->setAvailableLimit($this->getAvailableLimit());
                $pagerBlock->setUseContainer(false);
                $pagerBlock->setShowPerPage(false);
                $pagerBlock->setShowAmounts(false);
                $pagerBlock->setLimitVarName($this->getLimitVarName());
                $pagerBlock->setPageVarName($this->getPageVarName());
                $pagerBlock->setLimit($this->getLimit());
                $pagerBlock->setCollection($this->getCollection());
                $this->_pager = $pagerBlock;
            }          
        }
       
        return $this->_pager;
    }

    /**
     * Retrives next page url
     * @return string
     */
    public function getNextPageUrl()
    {
        return $this->_getPager()->getNextPageUrl();
    }

    /**
     * Retrives previous page url
     * @return string
     */
    public function getPreviousPageUrl()
    {
        return $this->_getPager()->getPreviousPageUrl();
    }

    /**
     * Retrives is first flag
     * @return boolean
     */
    public function isFirst()
    {
        return $this->_getPager()->isFirstPage();
    }

    /**
     * Retrives is last flag
     * @return boolean
     */
    public function isLast()
    {
        return $this->_getPager()->isLastPage();
    }
}
