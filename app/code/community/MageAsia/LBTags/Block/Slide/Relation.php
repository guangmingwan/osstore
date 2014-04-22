<?php
class MageAsia_LBTags_Block_Slide_Relation extends Mage_Catalog_Block_Product_Abstract
{
	
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
			
			
			 public function getTop10Tags(){
     
		
					$homepagetags=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_polulartags_block_size');
				 	$identifier_code =  trim($this->getRequest()->getParam('identifier_code'));
					$items = Mage::getModel('lbtags/tags')->getCollection() 
					 ->addFieldToFilter('status', array('eq'=>1)) 
						 ->setOrder('tags_id', 'desc')
						 
					  ->setPageSize($homepagetags)
					  ->setCurPage(1)
					; 
					 
					 //$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
					 return $items;
					
			}
	
}