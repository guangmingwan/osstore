<?php

class MageAsia_LBTags_Block_Adminhtml_Tags_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
          'header'    => Mage::helper('lbtags')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'tags_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('lbtags')->__('Tags Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
	  $this->addColumn('tagcount', array(
          'header'    => Mage::helper('lbtags')->__('Product Item'),
          'align'     =>'left',
          'index'     => 'tagcount',
      ));
 $this->addColumn('identifier', array(
          'header'    => Mage::helper('lbtags')->__('Identifier'),
          'align'     =>'left',
          'index'     => 'identifier',
      ));
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('lbtags')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('lbtags')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('lbtags')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('lbtags')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		
		  $this->addExportType('*/*/exportAllTagsCsv', Mage::helper('lbtags')->__('Export All Tages Information CSV'));
		  $this->addExportType('*/*/exportTags2ProductCsv', Mage::helper('lbtags')->__('Export Tags to Product CSV'));
		#$this->addExportType('*/*/exportProductCsv', Mage::helper('lbtags')->__('Export Product ID CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('lbtags')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tags_id');
        $this->getMassactionBlock()->setFormFieldName('lbtags');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('lbtags')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('lbtags')->__('Are you sure?')
        ));
$this->getMassactionBlock()->addItem('automatchcategory', array(
             'label'    => Mage::helper('lbtags')->__('Tags Auto Match Products '),
             'url'      => $this->getUrl('*/*/tagsmatchproduct'),
            
        ));
		
	 	$this->getMassactionBlock()->addItem('exportTags2ProductCsv', array(
             'label'    => Mage::helper('lbtags')->__('Export Tags to Product CSV'),
             'url'      => $this->getUrl('*/*/exportTags2ProductCsv'),
            
         ));
		
	 	 $this->getMassactionBlock()->addItem('retagcount', array(
            'label'    => Mage::helper('lbtags')->__('Reindex Tags Count'),
             'url'      => $this->getUrl('*/*/retagcount'),
            
         )); 
		
        $statuses = Mage::getSingleton('lbtags/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('lbtags')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('lbtags')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}