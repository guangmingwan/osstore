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

class MageWorx_CustomOptions_Block_Catalog_Product_View_Options_Type_Select extends Mage_Catalog_Block_Product_View_Options_Type_Select {

    static $isFirstOption = true;

    public function getValuesHtml() {
        $_option = $this->getOption();
        
        $helper = Mage::helper('customoptions');
        $displayQty = $helper->canDisplayQtyForOptions();
        $hideOutOfStockOptions = $helper->canHideOutOfStockOptions();
        $enabledInventory = $helper->isInventoryEnabled();
        $enabledDependent = $helper->isDependentEnabled();
        
        if ((version_compare(Mage::getVersion(), '1.5.0', '>=') && version_compare(Mage::getVersion(), '1.9.0', '<')) || version_compare(Mage::getVersion(), '1.10.0', '>=')) {
            $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());                                                    
        } else {
            $configValue= false;
        }
        
        $buyRequest = false;
        if ($helper->isQntyInputEnabled() && Mage::app()->getRequest()->getControllerName()!='product') {
            $quoteItemId = (int) $this->getRequest()->getParam('id');
            if ($quoteItemId) {
                $item = Mage::getSingleton('checkout/cart')->getQuote()->getItemById($quoteItemId);
                if ($item) {
                    $buyRequest = $item->getBuyRequest();
                    if ($_option->getType() != Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX) {
                        $optionQty = $buyRequest->getData('options_' . $_option->getId() . '_qty');
                        $_option->setOptionQty($optionQty);
                    }
                }
            }
        }
        
        if ($_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN || $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {            
            
            $require = '';
            if ($_option->getIsRequire()) {                
                if ($_option->getIsDependent()) $require = ' required-dependent'; else $require = ' required-entry';
            }
            
            $extraParams = ($enabledDependent && $_option->getIsDependent()?' disabled="disabled"':'');
            $select = $this->getLayout()->createBlock('core/html_select')
                    ->setData(array(
                        'id' => 'select_' . $_option->getId(),
                        'class' => $require . ' product-custom-option'
                    ));
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) {
                $select->setName('options[' . $_option->getid() . ']')
                        ->addOption('', $this->__('-- Please Select --'));
            } else {
                $select->setName('options[' . $_option->getid() . '][]');
                $select->setClass('multiselect' . $require . ' product-custom-option');
            }
            
            
            $setProdutQtyJs = '';
            $imagesHtml = '';
            $imagesJs = '';
            $dependentJs = '';
            $defaultFlag = false;
            
            $itemValueCount = count($_option->getValues());
            
            if ($enabledDependent && $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) $dependentJs .= 'dependentDefault["select_' . $_option->getId().'"] = [];';
            
            foreach ($_option->getValues() as $_value) {                
                $qty = '';

                if ($enabledInventory && $hideOutOfStockOptions && $_value->getCustomoptionsQty()==='0') continue;                
                
                $selectOptions = array();
                if ($enabledInventory && $_value->getCustomoptionsQty()==='0') {
                    $selectOptions['disabled'] = 'disabled';
                }
                if ($_value->getDefault() == 1 && !isset($selectOptions['disabled']) && !$configValue) {
                    if (!$enabledDependent || !$_option->getIsDependent()) $selectOptions['selected'] = 'selected';
                    if ($enabledDependent) {
                        if ($_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) {
                            $dependentJs .= 'dependentDefault["select_' . $_option->getId().'"] = '.$_value->getOptionTypeId().';';
                        } else {
                            $dependentJs .= 'dependentDefault["select_' . $_option->getId().'"]['.$_value->getOptionTypeId().'] = 1;';
                        }
                    }    
                    $defaultFlag = true;
                }

                if ($enabledInventory) {
                    if ($displayQty && substr($_value->getCustomoptionsQty(), 0, 1)!='x' && $_value->getCustomoptionsQty()!=='') {
                        $qty = ' (' . ($_value->getCustomoptionsQty() > 0 ? $_value->getCustomoptionsQty() : 'Out of stock') . ')';
                    }                
                    if (substr($_value->getCustomoptionsQty(), 0, 1)=='x') {
                        if (!$setProdutQtyJs) {
                            $setProdutQtyJs = 'optionsSetQtyProductData['.$_option->getId().'] = [];';
                        } 
                        $setProdutQtyJs .= 'optionsSetQtyProductData['.$_option->getId().']['.$_value->getOptionTypeId().']='.intval(substr($_value->getCustomoptionsQty(), 1)).';';
                    }
                }
                
                // if more than 50 IMGs
                if ($itemValueCount>50 && $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) {                
                    if (!$imagesHtml) {
                        $imagesHtml = $helper->getImgHtml($_value->getImagePath(), $_option->getId(), $_value->getOptionTypeId(), true);
                        $imagesJs = 'optionsImagesData['.$_option->getId().'] = [];';
                    }    
                    $arr = $helper->getImgHtml($_value->getImagePath(), $_option->getId(), $_value->getOptionTypeId(), true, true);
                    if (isset($arr['big_img_url'])) $imagesJs .= 'optionsImagesData['.$_option->getId().']['.$_value->getOptionTypeId().']=["'.$arr['url'].'", "'.$arr['big_img_url'].'"];';
                } else {
                    $imagesHtml .= $helper->getImgHtml($_value->getImagePath(), $_option->getId(), $_value->getOptionTypeId());
                }    

                $priceStr = $helper->getFormatedOptionPrice(
                        $this->_formatPrice(array(
                            'is_percent' => ($_value->getPriceType() == 'percent') ? true : false,
                            'pricing_value' => $_value->getPrice(true)
                                ), false)
                    );
                
                if ($enabledDependent) {
                    if ($_value->getDependentIds()) {                    
                        $dependentJs .= 'dependentData['.$_value->getOptionTypeId().'] = ['.$_value->getDependentIds().']; ';
                    }                
                    if ($_value->getInGroupId()) {
                        $dependentJs .= 'inGroupIdData['.$_value->getInGroupId().'] = {"disabled":'.($_option->getIsDependent()?'true':'false').', "select_'.$_option->getId().'":'.$_value->getOptionTypeId().'}; ';
                    }
                } 
                
                $select->addOption($_value->getOptionTypeId(), $_value->getTitle() . ' ' . $priceStr . $qty, $selectOptions);
            }
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
                $extraParams .= ' multiple="multiple"';
            }
                        
            if ($setProdutQtyJs) $setProdutQtyFunc = 'optionsSetQtyProduct.setQty('.$_option->getId().');'; else $setProdutQtyFunc = '';
            
            $showImgFunc = '';
            if ($imagesHtml) {
                if ($imagesJs) {
                    $showImgFunc = 'optionsImages.setImage('.$_option->getId().');';
                } else {
                    $showImgFunc = 'optionsImages.showImage('.$_option->getId().');';
                }    
            }    
            
            $select->setExtraParams('onchange="'.(($enabledDependent)?'dependentOptions.select(this);':'')                
                .((Mage::app()->getStore()->isAdmin())?'':'opConfig.reloadPrice();')
                .$setProdutQtyFunc
                .$showImgFunc.'"'.$extraParams);
            
            if ($configValue) {
                $select->setValue($configValue);
                if ($enabledDependent) $dependentJs .= 'dependentDefault["select_' . $_option->getId().'"] = '.$configValue.';';
            }
            
            if ((count($select->getOptions())>1 && $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) || (count($select->getOptions())>0 && $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE)) {
                $outHTML = $select->getHtml();
                if ($imagesHtml) {
                    $imagesHtml .= '<script type="text/javascript">'.$imagesJs.'</script>';                    
                    if ($helper->isImagesAboveOptions()) $outHTML = $imagesHtml.$outHTML; else $outHTML .= $imagesHtml;
                }    
                if ($defaultFlag) $outHTML.='<script type="text/javascript">'.$showImgFunc.'</script>';
                if ($dependentJs) $outHTML.='<script type="text/javascript">'.$dependentJs.'</script>';
                if ($setProdutQtyJs) {$outHTML.='<script type="text/javascript">'.$setProdutQtyJs.'optionsSetQtyProduct.hideQty();</script>'; $_option->setOptionsSetQtyProduct(1);}
                
                
                return $outHTML;
            }    
        
            
        } elseif ($_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO || $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX) {
            $selectHtml = '';
            $dependentJs = '';
            $setProdutQtyJs = '';
                        
            $require = '';
            if ($_option->getIsRequire()) {                
                if ($_option->getIsDependent()) $require = ' required-dependent'; else $require = ' validate-one-required-by-name';
            }
            
            $arraySign = '';
            switch ($_option->getType()) {
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO:
                    $type = 'radio';
                    $class = 'radio';
                    break;
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX:
                    $type = 'checkbox';
                    $class = 'checkbox';
                    $arraySign = '[]';
                    break;
            }
            $count = 1;
            foreach ($_option->getValues() as $_value) {
                $count++;
                $priceStr = $helper->getFormatedOptionPrice(
                        $this->_formatPrice(array(
                            'is_percent' => ($_value->getPriceType() == 'percent') ? true : false,
                            'pricing_value' => $_value->getPrice(true)
                        ))
                    );
                
                $qty = '';
                if ($enabledInventory && $hideOutOfStockOptions && $_value->getCustomoptionsQty()==='0') continue;
                
                $inventory = ($enabledInventory && $_value->getCustomoptionsQty()==='0') ? false : true;
                $disabled = (!$inventory) || ($enabledDependent && $_option->getIsDependent()) ? 'disabled="disabled"' : '';                
                
                if ($enabledInventory) {
                    if ($displayQty && substr($_value->getCustomoptionsQty(), 0, 1)!='x' && $_value->getCustomoptionsQty() !== '') {
                        $qty = ' (' . ($_value->getCustomoptionsQty() > 0 ? $_value->getCustomoptionsQty() : 'Out of stock') . ')';
                    }

                    if (substr($_value->getCustomoptionsQty(), 0, 1)=='x') {
                        if (!$setProdutQtyJs) {
                            $setProdutQtyJs = 'optionsSetQtyProductData['.$_option->getId().'] = [];';
                        } 
                        $setProdutQtyJs .= 'optionsSetQtyProductData['.$_option->getId().']['.$_value->getOptionTypeId().']='.intval(substr($_value->getCustomoptionsQty(), 1)).';';
                    }
                }    
                
                                
                if ($disabled && $enabledDependent && $helper->hideDependentOption() && $_option->getIsDependent()) $selectHtml .= '<li style="display: none;">'; else $selectHtml .= '<li>';
                                
                $selectHtml .= $helper->getImgHtml($_value->getImagePath(), $_option->getId());
                
                if ($configValue) {
                    $htmlValue = $_value->getOptionTypeId();
                    if ($arraySign) {
                        $checked = (is_array($configValue) && in_array($htmlValue, $configValue)) ? 'checked' : '';                        
                    } else {
                        $checked = ($configValue == $htmlValue ? 'checked' : '');
                    }
                } else {
                    $checked = ($_value->getDefault()==1 && !$disabled) ? 'checked' : '';
                }                
                
                if ($enabledDependent) {
                    if ($_value->getDependentIds()) {                    
                        $dependentJs .= 'dependentData['.$_value->getOptionTypeId().'] = ['.$_value->getDependentIds().']; ';
                    }                
                    if ($_value->getInGroupId() ) {
                        $dependentJs .= 'inGroupIdData['.$_value->getInGroupId().'] = {"disabled":'.($_option->getIsDependent()?'true':'false').', "out_of_stock":'.(($enabledInventory && $_value->getCustomoptionsQty()==='0')?'true':'false').', "options_'.$_option->getId().'_'.$count.'":1}; ';
                    }
                    if ($checked || ($_value->getDefault()==1 && $inventory)) $dependentJs .= 'dependentDefault["options_' . $_option->getId() . '_' . $count . '"] = 1;';
                }

                if ($setProdutQtyJs) $setProdutQtyFunc = 'optionsSetQtyProduct.setQty('.$_option->getId().');'; else $setProdutQtyFunc = '';
                if ($checked) $setProdutQtyJs .= $setProdutQtyFunc;                
                
                if ($helper->isQntyInputEnabled() && $_option->getQntyInput() && $_option->getType()==Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX) {                    
                    if ($buyRequest) $optionValueQty = $buyRequest->getData('options_'.$_option->getId().'_'.$_value->getOptionTypeId().'_qty'); else $optionValueQty = 0;
                    if (!$optionValueQty) $optionValueQty = 1;
                    $selectHtml .=
                        '<input ' . $disabled . ' ' . $checked . ' type="' . $type . '" class="' . $class . ' ' . $require . ' product-custom-option" onclick="$(\'options_'.$_option->getId().'_'.$_value->getOptionTypeId().'_qty\').disabled=!this.checked; if ($(\'options_'.$_option->getId().'_'.$_value->getOptionTypeId().'_qty\').value<=0) $(\'options_'.$_option->getId().'_'.$_value->getOptionTypeId().'_qty\').value=1; '.(($enabledDependent)?'dependentOptions.select(this);':'').((Mage::app()->getStore()->isAdmin())?'':'opConfig.reloadPrice();').$setProdutQtyFunc.'" name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId() . '_' . $count . '" value="' . $_value->getOptionTypeId() . '" />' .
                        '<span class="label">
                            <label for="options_' . $_option->getId() . '_' . $count . '">' . $_value->getTitle() . ' ' . $priceStr . $qty . '</label>
                            &nbsp;&nbsp;&nbsp;
                            <label><b>'.$helper->getDefaultOptionQtyLabel().'</b> <input type="text" class="input-text qty validate-greater-than-zero" value="'.$optionValueQty.'" maxlength="12" id="options_'.$_option->getId().'_'.$_value->getOptionTypeId().'_qty" name="options_'.$_option->getId().'_'.$_value->getOptionTypeId().'_qty" onchange="'.((Mage::app()->getStore()->isAdmin())?'':'opConfig.reloadPrice();').'" onKeyPress="if(event.keyCode==13){'.((Mage::app()->getStore()->isAdmin())?'':'opConfig.reloadPrice();').'}" '.($checked?$disabled:'disabled').'></label>
                         </span>';
                } else {
                    $selectHtml .=
                        '<input ' . $disabled . ' ' . $checked . ' type="' . $type . '" class="' . $class . ' ' . $require . ' product-custom-option" onclick="'.(($enabledDependent)?'dependentOptions.select(this);':'').((Mage::app()->getStore()->isAdmin())?'':'opConfig.reloadPrice();').$setProdutQtyFunc.'" name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId() . '_' . $count . '" value="' . $_value->getOptionTypeId() . '" />' .
                        '<span class="label"><label for="options_' . $_option->getId() . '_' . $count . '">' . $_value->getTitle() . ' ' . $priceStr . $qty . '</label></span>';
                }
                                                
                if ($_option->getIsRequire()) {
                    $selectHtml .= '<script type="text/javascript">' .
                            '$(\'options_' . $_option->getId() . '_' . $count . '\').advaiceContainer = \'options-' . $_option->getId() . '-container\';' .
                            '$(\'options_' . $_option->getId() . '_' . $count . '\').callbackFunction = \'validateOptionsCallback\';' .
                            '</script>';
                }
                $selectHtml .= '</li>';
            }
            
            if ($selectHtml) $selectHtml = '<ul id="options-' . $_option->getId() . '-list" class="options-list">'.$selectHtml.'</ul>';
            self::$isFirstOption = false;
            
            if ($dependentJs) $selectHtml.='<script type="text/javascript">'.$dependentJs.'</script>';
            if ($setProdutQtyJs) {$selectHtml.='<script type="text/javascript">'.$setProdutQtyJs.'optionsSetQtyProduct.hideQty();</script>'; $_option->setOptionsSetQtyProduct(1);}
            
            return $selectHtml;
        }
    }

}