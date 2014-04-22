<?php

class MageAsia_LBTags_Block_Adminhtml_Tags_Gridtagall extends Mage_Adminhtml_Block_Widget_Grid
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
          'header'    => Mage::helper('lbtags')->__('tags_id'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'tags_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('lbtags')->__('name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
	  
	      $this->addColumn('meta_title', array(
          'header'    => Mage::helper('lbtags')->__('meta_title'),
          'align'     =>'left',
          'index'     => 'meta_title',
      ));
	  
	      $this->addColumn('meta_keywords', array(
          'header'    => Mage::helper('lbtags')->__('meta_keywords'),
          'align'     =>'left',
          'index'     => 'meta_keywords',
      ));
	      $this->addColumn('meta_description', array(
          'header'    => Mage::helper('lbtags')->__('meta_description'),
          'align'     =>'left',
          'index'     => 'meta_description',
      ));
	  
	      $this->addColumn('comments', array(
          'header'    => Mage::helper('lbtags')->__('comments'),
          'align'     =>'left',
          'index'     => 'comments',
      ));
	     
		#$this->addExportType('*/*/exportProductCsv', Mage::helper('lbtags')->__('Export Product ID CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('lbtags')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
       
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}