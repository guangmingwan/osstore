<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.2
 *
 */

class EcommerceTeam_EasyCheckout_Model_System_Conf_Source_Mode{
	
    /**
     * Options getter
     *
     * @return array
     */
    
    public function toOptionArray()
    {
        
        return array(
        	array('label'=>Mage::helper('ecommerceteam_echeckout')->__('1 Column'), 'value'=>0),
        	array('label'=>Mage::helper('ecommerceteam_echeckout')->__('3 Columns'), 'value'=>1),
        );
    }
}