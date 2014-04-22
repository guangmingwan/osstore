<?php

class MageAsia_LBTags_Model_Mysql4_Tags_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lbtags/tags');
    }
	public function addTagsNameFilter($tagname){
		$query	=	$this->getSelect() ;
		if($tagname=="0-9"){
			$queryString="(";
			for($k=0;$k<10;$k++)
			{
		
				$queryString.="name like '".$k."%' " ;
				if($k!=9){
					$queryString.=" OR ";
				}
			}
			$queryString.=")";
			$query->where($queryString);
		}else{
			$query->where("name like '".$tagname."%'", $tagname);	
		}

		return $this;
    }
	public function addTagsSearchByFilter($identifier)
	{	
			$query=$this->getSelect()
			->where( "`tags_id` in (   ?   )",$identifier);
			return $this;
			
	}
	public function addTagsIdentifierFilter($identifier)
	{	
			$query=$this->getSelect()
			->where( '`identifier` = ?',$identifier);
			return $this;
			
	}
}