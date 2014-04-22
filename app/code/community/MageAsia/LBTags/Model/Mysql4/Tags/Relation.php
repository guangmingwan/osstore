<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Tag
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Tag Relation resource model
 *
 * @category   Mage
 * @package    Mage_Tag
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class MageAsia_LBTags_Model_Mysql4_Tags_Relation extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize resource connection and define table resource
     *
     */
    protected function _construct()
    {
        $this->_init('lbtags/tags_relation', 'tag_relation_id');
    }

     public function getProductById($id)
    {
		
		  
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'product_id')
            ->where("{$this->getMainTable()}.tags_id=?", $id);

        
    
 
        return $this->_getReadAdapter()->fetchCol($select);
    }
    public function getProductIds($model)
    {
		
	 	 
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'product_id')
            ->where("{$this->getMainTable()}.tags_id=?", $model->getTagId());

    
        return $this->_getReadAdapter()->fetchCol($select);
    }
	
	 public function getProductIdList($tagid)
    {
		
	 	 
      $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'product_id')
            ->where("{$this->getMainTable()}.tags_id=?", $tagid);
		$productcoll=$this->_getReadAdapter()->fetchCol($select);
		 $idlist=array();
		 foreach($productcoll as $k=>$item){
			 if($item>0){
				 $sku=Mage::getModel("catalog/product")->load($item)->getSku();
				 if($sku!=""){
						$idlist[]=$sku; 
				 }
			 }
		 }
	 
		return $idlist;  /**/
    }
	 public function getTagsIdList($productid)
    {
		
	 	 
      $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'tags_id')
            ->where("{$this->getMainTable()}.product_id=?", $productid);
		$productcoll=$this->_getReadAdapter()->fetchCol($select);
		 
		 $idlist=array();
		 foreach($productcoll as $k=>$item){
			 if($item>0){
				 
						$idlist[]=$item; 
				 
			 }
		 }
	 
		return $idlist;  /**/
    }
    /**
     * Retrieve related to product tag ids
     *
     * @param Mage_Tag_Model_Tag_Relation $model
     * @return array
     */
    public function getRelatedTagIds($model)
    {
        $productIds = (is_array($model->getProductId())) ? $model->getProductId() : array($model->getProductId());
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'tags_id')
            ->where("product_id IN(?)", $productIds)
            ->order('tags_id');
			 
			 
        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Deactivate tag relations by tag and customer
     *
     * @param int $tagId
     * @param int $customerId
     * @return Mage_Tag_Model_Mysql4_Tag_Relation
     */
    public function deactivate($tagId, $customerId)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('tags_id = ?', $tagId) . ' AND ';
        $condition.= $this->_getWriteAdapter()->quoteInto('customer_id = ?', $customerId);
        $data = array('active' => Mage_Tag_Model_Tag_Relation::STATUS_NOT_ACTIVE);
        $this->_getWriteAdapter()->update($this->getMainTable(), $data, $condition);
        return $this;
    }

    /**
     * Add TAG to PRODUCT relations
     *
     * @param Mage_Tag_Model_Tag_Relation $model
     * @return Mage_Tag_Model_Mysql4_Tag_Relation
     */
    public function addRelations($model,$isdelete=true)
    {
        $addedIds = $model->getAddedProductIds();

        $select = $this->_getWriteAdapter()->select()
            ->from($this->getMainTable(), 'product_id')
            ->where("tags_id = ?", $model->getTagsId())
           // ->where("store_id = ?", $model->getStoreId())
            ;
        $oldRelationIds = $this->_getWriteAdapter()->fetchCol($select);

        $insert = array_diff($addedIds, $oldRelationIds);
        $delete = array_diff($oldRelationIds, $addedIds);
		//die(print_r($delete));
        if (!empty($insert)) {
            $insertData = array();
            foreach ($insert as $value) {
                $insertData[] = array(
                    'tags_id'        => $model->getTagsId(),
                   // 'store_id'      => $model->getStoreId(),
                    'product_id'    => $value,
                  
                    'created_at'    => $this->formatDate(time())
                );
            }
            $this->_getWriteAdapter()->insertMultiple($this->getMainTable(), $insertData);
        }
		if($isdelete==true){
		  if (!empty($delete)) {
				$this->_getWriteAdapter()->delete($this->getMainTable(), array(
					$this->_getWriteAdapter()->quoteInto('product_id IN (?)', $delete),
				   // $this->_getWriteAdapter()->quoteInto(
				   			//'store_id = ?', $model->getStoreId()),'customer_id IS NULL'
				));
			} 
		}

        return $this;
    }
	public function deleteRelations($tagsid)
	{
			$select = $this->_getWriteAdapter()->select()
			->from($this->getMainTable(), 'product_id')
			->where("tags_id = ?", $tagsid)
			;
			 
			$oldRelationIds = $this->_getWriteAdapter()->fetchCol($select);
			$delete = array_diff($oldRelationIds, $addedIds);
			 
			if (!empty($delete)) {
				$this->_getWriteAdapter()->delete($this->getMainTable(), array(
				$this->_getWriteAdapter()->quoteInto('product_id IN (?)', $delete),
				// $this->_getWriteAdapter()->quoteInto('store_id = ?', $model->getStoreId()),'customer_id IS NULL'
				));
			} 
			   return $this; 
	}
	
	
}
