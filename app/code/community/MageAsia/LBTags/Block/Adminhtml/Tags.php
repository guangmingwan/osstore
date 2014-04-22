<?php
class MageAsia_LBTags_Block_Adminhtml_Tags extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_tags';
    $this->_blockGroup = 'lbtags';
    $this->_headerText = Mage::helper('lbtags')->__('Tags Manager');
    $this->_addButtonLabel = Mage::helper('lbtags')->__('Add Tags');
	
	$data = array(
                'label' =>  'Import Product Tags',
                'onclick'   => "setLocation('".$this->getUrl('*/*/productimport')."')"
                );

    $this->addButton ('product_import_data', $data, 0, 100,  'header', 'header');  
	
	
	$data = array(
                'label' =>  'Import Tags',
                'onclick'   => "setLocation('".$this->getUrl('*/*/import')."')"
                );

    $this->addButton ('import_data', $data, 0, 101,  'header', 'header');  
	$data = array(
                'label' =>  'Auto Product Tags',
                'onclick'   => "setLocation('".$this->getUrl('*/*/runtags')."')"
                );

    $this->addButton ('autoproducttags', $data, 0, 102,  'header', 'header');  
	$data = array(
                'label' =>  'Tags Auto Match Product',
                'onclick'   => "setLocation('".$this->getUrl('*/*/tagsmatchproduct')."')"
                );

    $this->addButton ('tagsmatchproduct', $data, 0, 103,  'header', 'header');  
	 
	
	
    parent::__construct();
  }
}