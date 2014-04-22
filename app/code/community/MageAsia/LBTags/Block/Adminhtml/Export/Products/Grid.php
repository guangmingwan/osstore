<?php

class MageAsia_LBTags_Block_Adminhtml_Export_Products_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
	 
      parent::__construct();
     # $this->setId('tagsGrid');
    #  $this->setDefaultSort('tags_id');
    #  $this->setDefaultDir('DESC');
     # $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
       $collection = Mage::getModel('catalog/product')->getCollection()->setPageSize(10);
 
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	  
	  
      $this->addColumn('sku', array(
          'header'    => Mage::helper('lbtags')->__('Sku'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'sku',
      ));

      $this->addColumn('id', array(
          'header'    => Mage::helper('lbtags')->__('Tagid'),
          'align'     =>'left',
		  'filter'    => false,
          'sortable'  => false,
          'index'     => 'id',
		  'renderer'  => 'lbtags/adminhtml_export_products_renderer_tags',
		  
		  
      ));
	 
		
		 
	  
      return parent::_prepareColumns();
  }
 

}