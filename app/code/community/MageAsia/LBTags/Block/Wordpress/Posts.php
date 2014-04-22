<?php
class MageAsia_LBTags_Block_Wordpress_Posts extends Mage_Core_Block_Template
{
	 protected $_limit = 10; 
	
		public function _prepareLayout()
    {
		 
		
		return parent::_prepareLayout();
    }
	
	 
 	  public function getPosts(){
     
		$lbtags=Mage::Registry("lbtags_item");
		$tagname=$lbtags->getName();
	# 	$homepagetags=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_polulartags_home_size');
	 	 $collection=Mage::getResourceModel('wordpress/post_collection')
	 		->addIsPublishedFilter()
		 #	->addFieldToFilter('post_title', array('like' => $tagname)) 
			
		#	->addFieldToFilter('post_content', array('like' => $tagname));
		
		;
		$collection->getSelect()->where(" post_title like  '%".$tagname."%' OR post_content like '%".$tagname."%'")->limit(12);
		
 
			 return $collection;
			
	}
	
	public function searchWord($collection,$QueryText){
				$MaxQueryWords=100;
				$adapter = Mage::getSingleton('core/resource')->getConnection('core_write');
        		$query = Mage::helper('catalogsearch')->getQuery();
		        $words = Mage::helper('core/string')->splitWords($QueryText, true, $MaxQueryWords);
		        $like = '(';
		        $i =  0;
		        $wordsCopy = Mage::helper('lbtags')->cleanWords($words);
		        if (!count($wordsCopy)) {
		        	$wordsCopy = $words;
		        }
				$likearr=array();
				$likearr[]= ' f.data_index like "%'.trim($QueryText).'%" ';
		        foreach ($wordsCopy as $word) {
		        	 
		    			$likearr[]= ' f.data_index like "%'.$word.'%" ';
		        	 
		            $i++;
		        }
				$like.=implode(" OR ",$likearr);
		        $like .= ')';
		        $collection->getSelect()->joinInner(
		        	array('f' => Mage::getSingleton('core/resource')->getTableName('catalogsearch_fulltext')),
		        	'e.entity_id = f.product_id and f.store_id='.Mage::app()->getStore()->getId().' and '.$like.'',
		        	''
		        );
         
        	 return $collection;
        	
        
	}
	
	  
	
    

     
}