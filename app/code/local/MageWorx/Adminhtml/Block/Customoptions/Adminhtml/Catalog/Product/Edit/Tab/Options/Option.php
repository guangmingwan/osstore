<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomOptions
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Advanced Product Options extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomOptions
 * @author     MageWorx Dev Team
 */

class MageWorx_Adminhtml_Block_Customoptions_Adminhtml_Catalog_Product_Edit_Tab_Options_Option extends MageWorx_Adminhtml_Block_Customoptions_Adminhtml_Catalog_Product_Edit_Tab_Options_Option_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('customoptions/catalog-product-edit-options-option.phtml');
    }
    
    public function getViewIGI($IGI) {        
        return (($IGI<65536)?$IGI:floor($IGI/65535).'x'.$IGI%65535);
    }

    public function getOptionValues() {                
        
        $optionsArr = $this->getProduct()->getOptions();        
        // if option enabled = no && hasOptions = 0
        if (!$optionsArr) $optionsArr = $this->getProduct()->getProductOptionsCollection();
        
        
        if (!$this->_values) {
            $values = array();            
            $scope = (int) Mage::app()->getStore()->getConfig(Mage_Core_Model_Store::XML_PATH_PRICE_SCOPE);                        
            
            foreach ($optionsArr as $option) {

                /* @var $option Mage_Catalog_Model_Product_Option */

                $this->setItemCount($option->getOptionId());                
                $value = array();

                $value['id'] = $option->getOptionId();
                $value['template_title'] = ($option->getGroupTitle())?Mage::helper('customoptions')->__('Predefined Option:').' '.$option->getGroupTitle():'';
                $value['item_count'] = $this->getItemCount();
                $value['option_id'] = $option->getOptionId();
                $value['title'] = $this->htmlEscape($option->getTitle());
                $value['type'] = $option->getType();
                $value['is_require'] = $option->getIsRequire();
                $value['is_enabled'] = $option->getIsEnabled();
                $value['is_dependent'] = $option->getIsDependent();
                
                $value['customoptions_is_onetime'] = $option->getCustomoptionsIsOnetime();
                $value['qnty_input'] = ($option->getQntyInput()?'checked':'');
                $value['qnty_input_disabled'] = (($option->getType()=='drop_down' || $option->getType()=='radio' || $option->getType()=='checkbox')?'':'disabled');
                
                $value['sort_order'] = $option->getSortOrder();
                $value['image_button_label'] = ($option->getImagePath()?Mage::helper('customoptions')->__('Change Image'):Mage::helper('customoptions')->__('Add Image'));
                
                $value['description'] = $this->htmlEscape($option->getDescription());
                if (Mage::helper('customoptions')->isCustomerGroupsEnabled() && $option->getCustomerGroups() != null) {
                    $value['customer_groups'] = $option->getCustomerGroups();
                }
                
                $value['in_group_id'] = $option->getInGroupId();
                $value['in_group_id_view'] = $this->getViewIGI($option->getInGroupId());
                
                

                if ($this->getProduct()->getStoreId() != '0') {
                    $value['checkboxScopeTitle'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'title', is_null($option->getStoreTitle()));
                    $value['scopeTitleDisabled'] = is_null($option->getStoreTitle()) ? 'disabled' : null;
                    $value['checkboxScopeDescription'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'description', is_null($option->getStoreDescription()));
                    $value['scopeDescriptionDisabled'] = is_null($option->getStoreDescription()) ? 'disabled' : null;
                }

                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                $tablePrefix = (string) Mage::getConfig()->getTablePrefix();

                $select = $connection->select()->from($tablePrefix . 'custom_options_relation')->where('option_id = ' . $option->getOptionId());
                $relation = $connection->fetchRow($select);

                if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                    $i = 0;
                    $itemCount = 0;
                    foreach ($option->getValues() as $_value) {
                        /* @var $_value Mage_Catalog_Model_Product_Option_Value */
                        
                        $dependentIds = array();
                        $dependentIdsTmp = explode(',', $_value->getDependentIds());                        
                        foreach ($dependentIdsTmp as $d_id) {
                            $dependentIds[] = $this->getViewIGI($d_id);
                        }
                        
                        
                        $value['optionValues'][$i] = array(
                            'item_count' => max($itemCount, $_value->getOptionTypeId()),
                            'option_id' => $_value->getOptionId(),
                            'option_type_id' => $_value->getOptionTypeId(),
                            'title' => $this->htmlEscape($_value->getTitle()),
                            'price' => $this->getPriceValue($_value->getPrice(), $_value->getPriceType()),
                            'price_type' => $_value->getPriceType(),
                            'sku' => $this->htmlEscape($_value->getSku()),
                            'customoptions_qty' => $_value->getCustomoptionsQty(),                            
                            'image_button_label' => ($_value->getImagePath()?Mage::helper('customoptions')->__('Change Image'):Mage::helper('customoptions')->__('Add Image')),
                            'sort_order' => $_value->getSortOrder(),
                            'checked' => $_value->getDefault() != 0 ? 'checked' : '',
                            'default_type' => (($option->getType()=='checkbox' || $option->getType()=='multiple') ? 'checkbox' : 'radio'),
                            'in_group_id' => $_value->getInGroupId(),
                            'in_group_id_view' => $this->getViewIGI($_value->getInGroupId()),
                            'dependent_ids' => implode(',', $dependentIds)
                        );

                        $imgHtml = Mage::helper('customoptions')->getImgHtml($_value->getImagePath(), $option->getId(), $_value->getOptionTypeId());
                        if ($imgHtml) $value['optionValues'][$i]['image'] = $imgHtml;

                        if ($this->getProduct()->getStoreId() != '0') {
                            $value['optionValues'][$i]['checkboxScopeTitle'] = $this->getCheckboxScopeHtml($_value->getOptionId(), 'title', is_null($_value->getStoreTitle()), $_value->getOptionTypeId());
                            $value['optionValues'][$i]['scopeTitleDisabled'] = is_null($_value->getStoreTitle()) ? 'disabled' : null;
                            
                            if ($scope==Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                                $value['optionValues'][$i]['checkboxScopePrice'] = $this->getCheckboxScopeHtml($_value->getOptionId(), 'price', is_null($_value->getstorePrice()), $_value->getOptionTypeId());
                                $value['optionValues'][$i]['scopePriceDisabled'] = is_null($_value->getStorePrice()) ? 'disabled' : null;
                            }
                        }
                        $i++;
                    }
                } else {
                    $value['price'] = $this->getPriceValue($option->getPrice(), $option->getPriceType());
                    $value['price_type'] = $option->getPriceType();
                    $value['sku'] = $this->htmlEscape($option->getSku());
                    $value['max_characters'] = $option->getMaxCharacters();
                    $value['file_extension'] = $option->getFileExtension();
                    $value['image_size_x'] = $option->getImageSizeX();
                    $value['image_size_y'] = $option->getImageSizeY();
                    
                    $imgHtml = Mage::helper('customoptions')->getImgHtml($option->getImagePath(), $option->getId());
                    if ($imgHtml) $value['image'] = $imgHtml;
                                        
                    if ($this->getProduct()->getStoreId()!='0' && $scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {                        
                        $value['checkboxScopePrice'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'price', is_null($option->getStorePrice()));
                        $value['scopePriceDisabled'] = is_null($option->getStorePrice()) ? 'disabled' : null;
                    }
                }
                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }
        return $this->_values;
    }

    public function getOneTimeSelectHtml() {        
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
                ->setData(array(
                    'id' => $this->getFieldId() . '_{{id}}_customoptions_is_onetime',
                    'class' => 'select'
                ))
                ->setName($this->getFieldName() . '[{{id}}][customoptions_is_onetime]')
                ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());        
        $select->setOptions(array_reverse($select->getOptions()));
        return $select->getHtml();
    }

    public function getCustomerGroupsMultiselectHtml() {
        $collection = Mage::getModel('customer/group')->getCollection();
        $customerGroups = array();

        foreach ($collection as $item) {
            $customerGroups[$item->getId()]['value'] = $item->getId();
            $customerGroups[$item->getId()]['label'] = $item->getCustomerGroupCode();
        }

        $select = $this->getLayout()->createBlock('adminhtml/html_select')
                ->setData(array(
                    'id' => $this->getFieldId() . '_{{id}}_customer_groups',
                    'class' => 'select multiselect',
                ))
                ->setExtraParams('multiple="multiple" size="5"')
                ->setName($this->getFieldName() . '[{{id}}][customer_groups][]')
                ->setOptions($customerGroups);

        return $select->getHtml();
    }
    
    public function getEnabledSelectHtml() {        
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
                ->setData(array(
                    'id' => $this->getFieldId() . '_{{id}}_is_enabled',
                    'class' => 'select'
                ))
                ->setName($this->getFieldName() . '[{{id}}][is_enabled]')
                ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());        
        $select->setOptions($select->getOptions());
        return $select->getHtml();
    }
    
    public function getDependentSelectHtml() {        
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
                ->setData(array(
                    'id' => $this->getFieldId() . '_{{id}}_is_dependent',
                    'class' => 'select'
                ))
                ->setName($this->getFieldName() . '[{{id}}][is_dependent]')
                ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());        
        $select->setOptions(array_reverse($select->getOptions()));
        return $select->getHtml();
    }

}