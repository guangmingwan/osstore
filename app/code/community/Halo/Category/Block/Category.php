<?php
class Halo_Category_Block_Category extends Mage_Core_Block_Template
{
	public function getAllCategory() { 
		$helper = Mage::helper('catalog/category');
        return $helper->getStoreCategories();
	} 
	
	public function getAllSubCategory($category)
	{
		$children = $category->getChildren();
        $hasChildren = $children && $children->count();
		if ($hasChildren)
		{
			return $children;
		}
		return $children;
	}
	
	public function countParents()
	{
		$count = 0;
		$helper = Mage::helper('catalog/category');
        foreach($helper->getStoreCategories() as $cat)
		{
			$count++;
		}
		return $count;
	}
		
	public function countChildrens($category)
	{
		$count = 0;
		$children = $category->getChildren();
		foreach ($children as $child)
		{
			$count++;
		}
		return $count;
	}
	
	public function countProducts($catId)
	{
		
		$resource = Mage::getSingleton('core/resource'); 
		$read = $resource->getConnection('catalog_read'); 
		$categoryProductTable = $resource->getTableName('catalog/category_product'); 
		$select = $read->select() 
					   ->from(array('cp'=>$categoryProductTable), 'count(product_id) as c') 
					   ->where('cp.category_id=?',$catId); 
		$count  = $read->fetchRow($select); 
		$count  = $count['c'];
		return $count;
	}
	
	public function getCatImageUrl($catId)
	{
		$resource = Mage::getSingleton('core/resource'); 
		$read = $resource->getConnection('catalog_read'); 
		$categoryEntityVarcharTable = 'catalog_category_entity_varchar'; 
		$Eav = 'eav_attribute';
		$select = $read->select() 
						->from(array('ce'=>$categoryEntityVarcharTable), 'value')
						->join(array('ea'=>$Eav), 'ce.attribute_id = ea.attribute_id', array()) 
						->where('ce.entity_id='.$catId)
						->where('ea.attribute_code="image"'); 
		$img = $read->fetchRow($select); 
		$img = $img['value'];
		if(empty($img))
		{
			$img = $this->getSkinUrl("images/nopic_cat.jpg");
		}
		else
		{
			$img = Mage::getBaseUrl('media').'catalog/category/'.$img;
		}
		return $img;
	}
}