<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Mobile
 * @version    1.6.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Mobile_Model_Core_Design_Package extends Mage_Core_Model_Design_Package
{
    /**
     * Retrives data helper
     * @return AW_Mobile_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('awmobile');
    }

    protected function _needToInject()
    {
        return (
                version_compare(Mage::getVersion(), '1.3.3.0', '<=') 
                && ( $this->_targetIsMobile() )
                );
    }

    protected function _targetIsMobile()
    {
        return (Mage::helper('awmobile')->getTargetPlatform() == AW_Mobile_Model_Observer::TARGET_MOBILE);
    }

    public function getSkinUrl($file=null, array $params=array())
    {
        if ($this->_needToInject()) {
            return $this->_getNewSkinUrl($file, $params);
        } else {
            return parent::getSkinUrl($file, $params);
        }
    }
    
    protected function _getNewSkinUrl($file = null, array $params = array())
    {
    	Varien_Profiler::start(__METHOD__);
    	if (empty($params['_type'])) {
    		$params['_type'] = 'skin';
    	}
    	if (empty($params['_default'])) {
    		$params['_default'] = false;
    	}
    	$this->updateParamDefaults($params);
    	if (!empty($file)) {
			$filename = $this->validateFile($file, $params);
			if (false===$filename) {

       			$params['_theme'] = $this->getFallbackTheme();
    			$filename = $this->validateFile($file, $params);
    			if (false===$filename) {
            		if ($this->getDefaultTheme()===$params['_theme']) {
            			return $params['_default'];
            		}
        			$params['_package'] = $this->getDefaultTheme();
        			$params['_theme'] = $this->getDefaultTheme();
        			$filename = $this->validateFile($file, $params);
        			if (false===$filename) {
        				return $params['_default'];
        			}
    			}

			}
    	}

    	$url = $this->getSkinBaseUrl($params).(!empty($file) ? $file : '');
    	Varien_Profiler::stop(__METHOD__);
    	return $url;
    }
    
    
    
    /**
     * Use this one to get existing file name with fallback to default
     *
     * $params['_type'] is required
     *
     * @param string $file
     * @param array $params
     * @return string
     */
    public function getFilename($file, array $params)
    {
        if ((strpos($file, 'awmobile.xml') !== false) && Mage::helper('awmobile')->getDisabledOutput()){
            return '';
        }
        
        if ($this->_needToInject() && (strpos($file, 'awmobile.xml') === false)) {
            return $this->_getNewFileName($file, $params);
        } else {
            return parent::getFilename($file, $params);
        }        
    }    
    
    /**
     * Use this one to get existing file name with fallback to default
     * (In this realisation was fixed small bug of Magento 1.3.3.0 and earler)
     * $params['_type'] is required
     *
     * @param string $file
     * @param array $params
     * @return string
     */    
    protected function _getNewFileName($file, array $params)
    {
    	Varien_Profiler::start(__METHOD__);
    	$this->updateParamDefaults($params);
		$filename = $this->validateFile($file, $params);
		if (false===$filename) {
			$params['_theme'] = $this->getFallbackTheme();
			$filename = $this->validateFile($file, $params);
			if (false===$filename) {
        		if ($this->getDefaultTheme()===$params['_theme']) {
        			return $params['_default'];
        		}
                # Fix for our extansions for 1.3.3.0
                if (strpos($file,".xml") === false){
                    $params['_package'] = $this->getDefaultTheme();
                }
    			$params['_theme'] = $this->getDefaultTheme();
    			$filename = $this->validateFile($file, $params);
    			if (false===$filename) {
    				return $params['_default'];
    			}
			}
		}
		Varien_Profiler::stop(__METHOD__);
		return $filename;        
    }

    public function setTheme()
    {       
        switch (func_num_args()) {
            case 1:
                foreach (array('layout', 'template', 'skin', 'locale') as $type) {
                    $param = func_get_arg(0);
                    if ( ($this->_targetIsMobile() && $param == $this->_helper()->getMobileTheme()) || !$this->_targetIsMobile() ){
                        parent::setTheme($type, $param);
                    }
                }
                break;

            case 2:
                $param1 = func_get_arg(0);
                $param2 = func_get_arg(1);
                if ( ($this->_targetIsMobile() && $param2 == $this->_helper()->getMobileTheme()) || !$this->_targetIsMobile() ){
                    parent::setTheme($param1, $param2);
                }
                break;
        }
        return $this;
    }

    /**
     * Set package name
     * In case of any problem, the default will be set.
     *
     * @param  string $name
     * @return Mage_Core_Model_Design_Package
     */
    public function setPackageName($name = '')
    {
        if($name == 'aw_iphone_force_check') { return parent::setPackageName(); }
        if (                 
                ($this->_targetIsMobile() && $name == $this->_helper()->getMobilePackage())
                || !$this->_targetIsMobile()
                || ($this->getArea() == 'adminhtml')
        ){

            return parent::setPackageName($name);

        } else {
            return $this;
        }
    }


}