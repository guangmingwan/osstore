<?php

class MageAsia_LBTags_Model_Tags_Relation extends Mage_Core_Model_Abstract
{
	  protected $productid;
    public function _construct()
    {
        parent::_construct();
        $this->_init('lbtags/tags_relation');
    }
	 protected function _getResource()
    {
        return parent::_getResource();
    }
	 public function getProductById($id)
    {
        $ids = $this->getData('product_ids');
        if (is_null($ids)) {
            $ids = $this->_getResource()->getProductById($id);
            $this->setProductIds($ids);
        }
        return $ids;
    }
	 public function getProductIds()
    {
        $ids = $this->getData('product_ids');
        if (is_null($ids)) {
            $ids = $this->_getResource()->getProductIds($this);
            $this->setProductIds($ids);
        }
        return $ids;
    }
	  public function getRelatedTagIds()
    {
        if (is_null($this->getData('related_tag_ids'))) {
            $this->setRelatedTagIds($this->_getResource()->getRelatedTagIds($this));
        }
        return $this->getData('related_tag_ids');
    }
	public function getProductIdList($tagid)
	{
		 
		return   $this->_getResource()->getProductIdList($tagid);
	}
		public function getTagsIdList($productid)
	{
		 
		return   $this->_getResource()->getTagsIdList($productid);
	}
	public function getProductId()
	{
		return $this->_productid;	
	}
	public function setProductId($proid)
	{
		 $this->_productid=$proid;
	}
	 public function deleteRelations($tagsid )
    {
       
        
     //   $this->setCustomerId(null);
       // $this->setStoreId($model->getStore());
        $this->_getResource()->deleteRelations($tagsid);
        return $this;
    }

	  public function addRelations(MageAsia_LBTags_Model_Tags $model, $productIds = array(),$isdelete=true)
    {
        $this->setAddedProductIds($productIds);
        $this->setTagsId($model->getTagsId());
     //   $this->setCustomerId(null);
       // $this->setStoreId($model->getStore());
        $this->_getResource()->addRelations($this,$isdelete);
        return $this;
    }
	
	 public function addRelationsNoDelete(MageAsia_LBTags_Model_Tags $model, $productIds = array())
    {
        $this->setAddedProductIds($productIds);
        $this->setTagsId($model->getTagsId());
     //   $this->setCustomerId(null);
       // $this->setStoreId($model->getStore());
        $this->_getResource()->addRelations($this,false);
        return $this;
    }
	public function TagsReCount($tagsid)
	{
		  $productids = $this->getProductById($tagsid);
		  $tagcount=(int)count($productids);
		   $write =  Mage::getSingleton('core/resource')->getConnection('core_write');  
		   $sql  = "Update  ".Mage::getSingleton('core/resource')->getTableName('lbtags/tags')." set tagcount={$tagcount} where tags_id=".$tagsid; 
		   $write->query($sql  ); 
		   return $tagcount;
		  
	}


}