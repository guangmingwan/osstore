<?php
class MageAsia_LBTags_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
	  protected $_collection;
	
	 public function getProductId()
    {
        if ($product = Mage::registry('current_product')) {
            return $product->getId();
        }
        return false;
    }
	
	
	   public function getTags()
    {
        return $this->_getCollection()->getItems();
    }
	
	 protected function _getCollection()
    {
        if( !$this->_collection && $this->getProductId() ) {
		 
            $modelRelation = Mage::getModel('lbtags/tags_relation');
			$modelRelation->setProductId($this->getProductId());
			$tagids=$modelRelation->getRelatedTagIds();
			$tagstring= implode(',',$tagids);
			 
			
				 $this->_collection =  Mage::getModel('lbtags/tags')->getCollection()
		 	->addFieldToFilter('tags_id',array('in'=>$tagids)) 
			 // ->addTagsSearchByFilter( $tagstring ) 
			 	 
			->setOrder('created_time', 'desc') ;
 
		/*	$model= Mage::getModel('lbtags/tags');
            $this->_collection = $model->getResourceCollection()
                ->addPopularity()
                ->addStatusFilter($model->getApprovedStatus())
                ->addProductFilter($this->getProductId())
                ->setFlag('relation', true)
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->setActiveFilter()
                ->load();*/
        }
        return $this->_collection;
    }


	
		  public function getLatestTags(){
     
		
					$homepagetags=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_polulartags_block_size');
				 	$identifier_code =  trim($this->getRequest()->getParam('identifier_code'));
					 
						if(strlen($identifier_code)>0){
							$identifier_code=substr($identifier_code,0,1);	
						}
					 
					$items = Mage::getModel('lbtags/tags')->getCollection() 
					 ->addFieldToFilter('status', array('eq'=>1)) 
						 ->setOrder('tags_id', 'desc')
						 ->addTagsNameFilter($identifier_code)
					  ->setPageSize($homepagetags)
					  ->setCurPage(1)
					; 
					 
					 //$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
					 return $items;
					
			}
			
			
			 
	
}