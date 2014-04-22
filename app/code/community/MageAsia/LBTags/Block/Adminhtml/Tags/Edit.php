<?php

class MageAsia_LBTags_Block_Adminhtml_Tags_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'lbtags';
        $this->_controller = 'adminhtml_tags';
        
        $this->_updateButton('save', 'label', Mage::helper('lbtags')->__('Save Tag'));
        $this->_updateButton('delete', 'label', Mage::helper('lbtags')->__('Delete Tag'));
		$this->_removeButton('reset');
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('tags_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'tags_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'tags_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('tags_data') && Mage::registry('tags_data')->getId() ) {
            return Mage::helper('lbtags')->__("Edit Tag '%s'", $this->htmlEscape(Mage::registry('tags_data')->getName()));
        } else {
            return Mage::helper('lbtags')->__('Add Tag');
        }
    }
}