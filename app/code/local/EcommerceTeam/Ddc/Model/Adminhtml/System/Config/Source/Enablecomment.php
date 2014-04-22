<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/
	
class Ecommerceteam_Ddc_Model_Adminhtml_System_Config_Source_Enablecomment{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
    	
    	$helper = Mage::helper('ecommerceteam_ddc');
    	
        return array(
            array('value' => 0, 'label'=>$helper->__('No')),
            array('value' => 1, 'label'=>$helper->__('Yes, Shipping Method Section')),
            array('value' => 2, 'label'=>$helper->__('Yes, Review Section')),
        );
    }

}