<?php

class Ecom_ProductPromotion_Block_Email_ProductSpecial extends Ecom_ProductPromotion_Block_Email_Abstract
{
   
	
	public function __construct() {
        parent::__construct();
        $this->setTemplate('productpromotion/email/product/special.phtml');
    }

	protected function _beforeToHtml(){
		$collection = $this->getSpecialProduct();
		foreach($collection as $product) {
			$this->addProduct($product);
		}
		return parent::_beforeToHtml();
	}

	public function getSpecialProduct() {
	   $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $collection = Mage::getResourceModel('catalog/product_collection');

        $collection->addAttributeToSelect('name')
			->addAttributeToSelect('product_url')
			->addAttributeToSelect('price')
 			->addAttributeToSelect('special_price')
			->addAttributeToSelect('small_image')
			->addWebsiteFilter(array($this->_website->getId()))
			->addAttributeToFilter('status',array('in'=>Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
            ->addAttributeToFilter('special_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('special_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'special_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'special_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
			
            ->addAttributeToSort('news_from_date', 'desc')
			->setPageSize($this->getProductsCount())

        ;

		return $collection;
   }
   
  }