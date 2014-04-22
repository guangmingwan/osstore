<?php
class Halo_Category_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/category?id=15 
    	 *  or
    	 * http://site.com/category/id/15 	
    	 */
    	/* 
		$category_id = $this->getRequest()->getParam('id');

  		if($category_id != null && $category_id != '')	{
			$category = Mage::getModel('category/category')->load($category_id)->getData();
		} else {
			$category = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($category == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$categoryTable = $resource->getTableName('category');
			
			$select = $read->select()
			   ->from($categoryTable,array('category_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$category = $read->fetchRow($select);
		}
		Mage::register('category', $category);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}