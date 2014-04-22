<?php

class MageAsia_LBTags_Block_Adminhtml_Tags_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('tags_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('lbtags')->__('Tag Information'));
  }

  protected function _beforeToHtml()
  {
	   if( Mage::registry('tags_data') && Mage::registry('tags_data')->getId() ) {
		   
      $this->addTab('form_tag', array(
          'label'     => Mage::helper('lbtags')->__('Tag Information'),
          'title'     => Mage::helper('lbtags')->__('Tag Information'),
          'content'   => $this->getLayout()->createBlock('lbtags/adminhtml_tags_edit_tab_form')->toHtml(), 
      ));
	    $this->addTab('form_tagproduct', array(
          'label'     => Mage::helper('lbtags')->__('Related Product'),
          'title'     => Mage::helper('lbtags')->__('Related Product'),
		   'url'       => $this->getUrl('*/*/assigned', array('_current' => true)),
           'class'     => 'ajax',
        //  'content'   => $this->getLayout()->createBlock('tags/adminhtml_LBTags_edit_tab_related','tags_assigned_grid')->toHtml(),
      )); 
	   }else{
		   
		    $this->addTab('form_tag', array(
          'label'     => Mage::helper('lbtags')->__('Tag Information'),
          'title'     => Mage::helper('lbtags')->__('Tag Information'),
          'content'   => $this->getLayout()->createBlock('lbtags/adminhtml_tags_edit_tab_form')->toHtml(),
      ));
	  
		   
	   }
     
      return parent::_beforeToHtml();
  }
}