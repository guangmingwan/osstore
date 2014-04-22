<?php

class MageAsia_LBTags_Model_Tags extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lbtags/tags');
    }
	
	
	public function getRelatedProductIds()
    {
        return Mage::getModel('lbtags/tags_relation')
            ->setTagId($this->getTagsId())
          //  ->setStoreId($this->getStoreId())
          //  ->setStatusFilter($this->getStatusFilter())
          //  ->setCustomerId(null)
            ->getProductIds();
    }
	public function searchTagtoProduct($tag,$tagsword,$max_product,$categoryid,$beforcount){
		
			
		$category_model = Mage::getModel('catalog/category'); //get category model
 
   		$_category = $category_model->load($categoryid); //$categoryid for which the child categories to be found        
 		$tagid=$tag->getTagsId();
        $all_child_categories = $category_model->getResource()->getAllChildren($_category); //array consisting of all child categories id
		$oldtags=$productids = Mage::getModel('lbtags/tags_relation') ->getProductById($tag->getTagsId());
		/// echo count($all_child_categories)."<br />";
		$tagsnamearray=array();
		if(count($all_child_categories)>0){
			$maxproductcount=ceil(count($all_child_categories)%$max_product);
			 
			echo "<br />Count Categories:".count($all_child_categories);
			echo "<br />Category/Product:".$maxproductcount;
			echo "<br />";
			for($k=0;$k<count($all_child_categories);$k++ ){
				
							$cat=$all_child_categories[$k]; 
							 
							$productModule=Mage::getModel("catalog/product") ->getCollection() 
								->addCategoryFilter($category_model->load($cat))
								  ->addAttributeToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)) 
								->addFieldToFilter(array(
									array('attribute'	=>'name'
									,
									'like'				=>'%'.$tagsword.'%'
									),array('attribute'	=>'description'
									,
									'like'				=>'%'.$tagsword.'%'
									),
									
								
								)) ;
								$productModule->getSelect()->order(new Zend_Db_Expr('RAND()'));
								 $productModule->getSelect()->limit($maxproductcount);
								if(count($oldtags)>0){
									$productModule->addFieldToFilter('entity_id',array('nin'=>$oldtags));
								
								}
								 
							 
								if(count($productModule)==0){
									continue;
								}
								echo "Product Count:".count($productModule)." | ";				
								foreach($productModule as $item){
										$tagsnamearray[]=$item->getId();
								}
								
			}
		
		}else{
								$productModule=Mage::getModel("catalog/product") ->getCollection() 
								 ->addAttributeToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)) 
								->addFieldToFilter(array(
									array('attribute'	=>'name'
									,
									'like'				=>'%'.$tagsword.'%'
									),array('attribute'	=>'description'
									,
									'like'				=>'%'.$tagsword.'%'
									),
								
								)) ;
								$productModule->getSelect()->order(new Zend_Db_Expr('RAND()'));
								 $productModule->getSelect()->limit($max_product);
								echo "Product Count:".count($productModule)." | ";			
								foreach($productModule as $item){
										$tagsnamearray[]=$item->getId();
								}
			
			
			
		} 			
			
						
						$diffend=array_diff($tagsnamearray,$oldtags);
						if(	count( $diffend ) >0){
							 //$model = Mage::getModel('lbtags/tags')->load($tag->getTagsId());
							 $dcount=count($oldtags);
							 $tagRelationModel = Mage::getModel('lbtags/tags_relation');
							 foreach($diffend as $itemb)
								{
									if($dcount<$max_product){
										 $tagRelationModel->addRelationsNoDelete($tag, array($itemb));
										 $dcount++;	
									}
								}
							
							 echo "TagsName: ".$tag->getName().".  <br />";
							 echo "Keyword: ".$tagsword.".  <br />";
						}
						$oldtags=$productids = Mage::getModel('lbtags/tags_relation') ->getProductById($tag->getTagsId()); 
						
						if(count($productids)<$max_product){
								$productModule=Mage::getModel("catalog/product") ->getCollection()  ->addAttributeToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)) ;
								  
								 $productModule->getSelect()->order(new Zend_Db_Expr('RAND()'));
								 $productModule->getSelect()->limit(30);
								$tagsitem=array();
								foreach($productModule as $item){
									$tagsitem[]=$item->getId();
								}
								$diffend=array_diff($tagsitem,$oldtags);
								$dcount=count($oldtags);
								echo "<br />-------SP-------";
								
								foreach($diffend as $itemb)
								{
									if($dcount<$max_product){
										echo " &nbsp;&nbsp;ADD proID:".$itemb;
									 $tagRelationModel = Mage::getModel('lbtags/tags_relation');
									 $tagRelationModel->addRelationsNoDelete($tag, array($itemb));	
										$dcount++;
									}
										
								}
								
								
								
						}
			 		
							
							return true;
						
	}
	
	public function getRandProductToTags($maxcount){
		
	}
	public function getTagTable()
	{
		return $this->getTable('lbtags/tags');	
	}
	public function addWrodtoTags($wordlist,$product)
	{
		 		
				 
				 if($wordlist!=""){
					 $wordlist=preg_split("/,/",$wordlist);
					 foreach($wordlist as $word)
					 {
						 $word=trim($word);
						 if($word==""){continue;}
						 	 
							$tagsdata=array();
							$tagsdata['name']=ucwords($word);
							$Identifier=Mage::helper("lbtags")->FixIdentifier($tagsdata['name']);
							$tagsdata['identifier']=$Identifier;
							$tagsdata['status']=1;
							$model = Mage::getModel('lbtags/tags');		
							$modelData=$model->getCollection()
								->addFieldToFilter("identifier",$Identifier); 
							 if(count($modelData)>0){ 
								$model=$modelData; 
								$model = Mage::getModel('lbtags/tags')->load($Identifier,'identifier');
								 
								 //$message="Duplicate item  for '".$tagsdata['name']."' ";
								 //Mage::getSingleton('adminhtml/session')->addError($message);
								 //continue;
							 } else {
							 	$model->setData($tagsdata);
								 try {
									
										if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
											$model->setCreatedTime(now())
												->setUpdateTime(now());
										} else {
											$model->setUpdateTime(now());
										}	
										
										$model->save();
								 
									} catch (Exception $e) {
				  						die($e->getMessage());
									}
									
									
								
							 }
							 
								 $tagRelationModel = Mage::getModel('lbtags/tags_relation');
						 	
								$tagRelationModel->addRelationsNoDelete($model, array($product->getId()));	
								
								echo "TagsName: ".$tagsdata['name'] ."<br />";
							 
					 }
				 }	
		
	}
	
	
}