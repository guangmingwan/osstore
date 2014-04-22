<?php

class MageAsia_LBTags_Model_Mysql4_Relation_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lbtags/tags_relation');
    }
	
	
}
