<?php

class MageAsia_LBTags_Block_Adminhtml_Export_Tags_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
	 
      parent::__construct();
      $this->setId('tagsGrid');
      $this->setDefaultSort('tags_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
     $collection = Mage::getModel('lbtags/tags')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	  
	  
      $this->addColumn('tags_id', array(
          'header'    => Mage::helper('lbtags')->__('Tagid'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'tags_id',
      ));

      $this->addColumn('productid', array(
          'header'    => Mage::helper('lbtags')->__('Product ID'),
          'align'     =>'left',
		  'filter'    => false,
          'sortable'  => false,
          'index'     => 'tags_id',
		  
		  
      ));
	 
		
		 
	  
      return parent::_prepareColumns();
  }
 

}