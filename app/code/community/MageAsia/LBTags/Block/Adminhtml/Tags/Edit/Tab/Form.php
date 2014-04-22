<?php

class MageAsia_LBTags_Block_Adminhtml_Tags_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('tags_form', array('legend'=>Mage::helper('lbtags')->__('Tag information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('lbtags')->__('Tag name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'tags[name]',
      ));

      $fieldset->addField('identifier', 'text', array(
          'label'     => Mage::helper('lbtags')->__('Identifier'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'tags[identifier]',
      ));

		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('lbtags')->__('Status'),
          'name'      => 'tags[status]',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('lbtags')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('lbtags')->__('Disabled'),
              ),
          ),
      ));
	  
	  	    $fieldset->addField('meta_title', 'text', array(
          'label'     => Mage::helper('lbtags')->__('Meta Title'),
          
          'name'      => 'tags[meta_title]',
      ));
	    $fieldset->addField('meta_keywords', 'text', array(
          'label'     => Mage::helper('lbtags')->__('Meta Keyword'),
         
          'name'      => 'tags[meta_keywords]',
      ));
	    $fieldset->addField('meta_description', 'textarea', array(
          'label'     => Mage::helper('lbtags')->__('Meta Description'),
         
           
          'name'      => 'tags[meta_description]',
      ));
      $fieldset->addField('comments', 'textarea', array(
          'label'     => Mage::helper('lbtags')->__('Comment'),
          
           
          'name'      => 'tags[comments]',
      ));
      
      if ( Mage::getSingleton('adminhtml/session')->getTagsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTagsData());
          Mage::getSingleton('adminhtml/session')->setTagsData(null);
      } elseif ( Mage::registry('tags_data') ) {
          $form->setValues(Mage::registry('tags_data')->getData());
      }
      return parent::_prepareForm();
  }
}