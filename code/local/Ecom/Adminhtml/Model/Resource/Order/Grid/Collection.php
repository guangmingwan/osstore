<?php

class Ecom_Adminhtml_Model_Resource_Order_Grid_Collection extends Mage_Sales_Model_Resource_Order_Grid_Collection
{
	public function addItem(Varien_Object $item)
    {
          $this->_addItem($item);
		  return $this;
    }
}
