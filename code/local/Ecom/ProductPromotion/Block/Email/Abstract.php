<?php

abstract class Ecom_ProductPromotion_Block_Email_Abstract extends Mage_Catalog_Block_Product_Abstract
{
     const DEFAULT_PRODUCTS_COUNT = 8;
	 const DEFAULT_COLUMN_COUNT = 4;

	 protected $_productsCount;
	
	/**
     * Product collection array
     *
     * @var array
     */
    protected $_products = array();

	/**
     * Website Model
     *
     * @var Mage_Core_Model_Website
     */
    protected $_website;

 	public function __construct() {
        parent::__construct();
		$this->setColumnCount(self::DEFAULT_COLUMN_COUNT);
    }

    public function setWebsite(Mage_Core_Model_Website $website) {
        $this->_website = $website;
        return $this;
    }



    /**
     * Convert price from default currency to current currency
     *
     * @param double $price
     * @param boolean $format             Format price to currency format
     * @param boolean $includeContainer   Enclose into <span class="price"><span>
     * @return double
     */
    public function formatPrice($price, $format = true, $includeContainer = true)
    {
        return $this->getStore()->convertPrice($price, $format, $includeContainer);
    }

    /**
     * Reset product collection
     *
     */
    public function reset()
    {
        $this->_products = array();
    }

    /**
     * Add product to collection
     *
     * @param Mage_Catalog_Model_Product $product
     */
    public function addProduct(Mage_Catalog_Model_Product $product)
    {
		$this->_products[$product->getId()] = $product;
    }

    /**
     * Retrieve product collection array
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->_products;
    }

    /**
     * Get store url params
     *
     * @return string
     */
    protected function _getUrlParams()
    {
        return array(
            '_store'        => $this->getStore(),
            '_store_to_url' => true
        );
    }

    public function setProductsCount($count) {
        $this->_productsCount = $count;
        return $this;
    }


    public function getProductsCount()  {
        if (null === $this->_productsCount) {
            $this->_productsCount = self::DEFAULT_PRODUCTS_COUNT;
        }
        return $this->_productsCount;
    }
}
