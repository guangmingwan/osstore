<?php
class MageWorx_Adminhtml_Block_Customoptions_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Select extends
Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Select {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('customoptions/catalog-product-edit-options-type-select.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('add_select_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Add New Row'),
                            'class' => 'add add-select-row',
                            'id' => 'add_select_row_button_{{option_id}}',
                        ))
        );

        $this->setChild('delete_select_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Delete Row'),
                            'class' => 'delete delete-select-row icon-btn',
                        ))
        );

        $this->setChild('add_image_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => '{{image_button_label}}',
                            'class' => 'add',                            
                            'id' => 'new-option-file-{{option_id}}-{{select_id}}',
                            'onclick' => 'selectOptionType.createFileField(this.id)'                            
                        )));
        return parent::_prepareLayout();
    }

    public function getAddButtonHtml() {
        return $this->getChildHtml('add_select_row_button');
    }

    public function getDeleteButtonHtml() {
        return $this->getChildHtml('delete_select_row_button');
    }

    public function getAddImageButtonHtml() {
        return $this->getChildHtml('add_image_button');
    }

    public function getPriceTypeSelectHtml() {
        $this->getChild('option_price_type')
                ->setData('id', 'product_option_{{id}}_select_{{select_id}}_price_type')
                ->setName('product[options][{{id}}][values][{{select_id}}][price_type]');

        return parent::getPriceTypeSelectHtml();
    }

    public function getValueImages() {
        if (Mage::helper('customoption')->isCustomOptionFile($option->getId())) {
            $impOption = array(
                'label' => Mage::helper('multifees')->__('Delete Image'),
                'url' => $this->getUrl('*/*/getImage', array('option' => $option->getId())),
                'id' => $option->getId()
            );
            $value['image'] = Mage::helper('multifees')->getOptionImgView(new Varien_Object($impOption));
        }
    }

}
