<?php

class Ecom_ProductPromotion_Block_Email_ProductNew extends Ecom_ProductPromotion_Block_Email_Abstract
{
	public function __construct() {
        parent::__construct();
        $this->setTemplate('productpromotion/email/product/new.phtml');
    }
	
	protected function _beforeToHtml(){
		$collection = $this->getNewProduct();
		//echo $collection->getSelect();exit;
		foreach($collection as $product) {
			//echo $product->getSku() . "<br/>";
			$this->addProduct($product);
		}
		return parent::_beforeToHtml();
	}

	public function getNewProduct() {
	   $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

         $collection = Mage::getResourceModel('catalog/product_collection');
      
//->addMinimalPrice()
  //          ->addFinalPrice()
    //        ->addTaxPercents()
    //        ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
    //        ->addUrlRewrite()
     //$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());   
        $collection->addAttributeToSelect('name')
			->addAttributeToSelect('product_url')
			->addAttributeToSelect('price')
 			->addAttributeToSelect('special_price')
			->addAttributeToSelect('small_image')
			->addWebsiteFilter(array($this->_website->getId()))
			->addAttributeToFilter('status',array('in'=>Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
            ->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
			
            ->addAttributeToSort('news_from_date', 'desc')
			->setPageSize($this->getProductsCount())

        ;

		return $collection;
   }

   
}
