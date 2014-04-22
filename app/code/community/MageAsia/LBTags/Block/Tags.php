<?php
class MageAsia_LBTags_Block_Tags extends Mage_Catalog_Block_Product_Abstract
{
	public   $breadcrumbs;
	public $idlist=null;
	public $tag=null;
	public $pagetotal=null;
	public $mageasia_tags_detail_size=24;
	public function _prepareLayout()
    {
		
		
	 	$identifier_code =  trim($this->getRequest()->getParam('identifier_code'));
		$tagCollection = Mage::getModel('lbtags/tags')
	 
	    ->load($identifier_code, 'identifier');
		  
		
		 $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', 
		
		array('label'=>Mage::helper('lbtags')->__('Home'), 
				'title'=>Mage::helper('lbtags')->__('Go to Home Page'), 
				'link'=>Mage::getBaseUrl())
		);
		$breadcrumbs->addCrumb('tags', 
		
		array('label'=>Mage::helper('lbtags')->__('Tags'), 
				'title'=>Mage::helper('lbtags')->__('Go to Tags Page'), 
				'link'=>Mage::helper('lbtags')->getURL())
		);
		$tagcode=substr(ucfirst($tagCollection->getName()),0,1);
		$breadcrumbs->addCrumb('tagsitem', 
		
		array('label'=>$tagcode, 
				'title'=>$tagcode, 
				'link'=>Mage::helper('lbtags')->getURL().$tagcode
				 )
		);
		
	 $breadcrumbs->addCrumb('tagsitemtitle', 
		
		array('label'=>$tagCollection->getName(), 
				'title'=>$tagCollection->getName(), 
				 )
		);
		
		return parent::_prepareLayout();
    }
    
     public function getTags()     
     { 
	 
	 	 
		  $page = (int)$this->getRequest()->getParam('identifier_page');
		//$tagCollection=Mage::registry('tagsdata');
		$identifier_code =  trim($this->getRequest()->getParam('identifier_code'));
		$tagCollection = Mage::getModel('lbtags/tags')
	 
	    ->load($identifier_code, 'identifier');
		$comment=$tagCollection->getComments();
		if($comment==""){
			$comment= Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagsdetail_comment');
		} 
			
			$comment=str_replace("%title%",$tagCollection->getName(),$comment);
			$comment=str_replace("%metatitle%",$tagCollection->getMeta_title(),$comment);
			$comment=str_replace("%metakeywords%",$tagCollection->getMeta_keywords(),$comment);
			$comment=str_replace("%metadescription%",$tagCollection->getMeta_description(),$comment);
		 
		$tagCollection->setComments($comment);
		
		$this->tag=$tagCollection;
		
		$this->mageasia_tags_detail_size=$mageasia_tags_detail_size=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_detail_size'); 
		 
		$this->idlist=$productids = Mage::getModel('lbtags/tags_relation') 
		->getProductById($tagCollection->getTagsId());
		
	 
		 $collectionpro = Mage::getResourceModel('catalog/product_collection');
		   $collection = $this->_addProductAttributesAndPrices($collectionpro);
		   
		   
		   
		   $word=$tagCollection->getName();
		   
		# $collection ->addAttributeToFilter('entity_id', array('in' =>$productids)) ;
		 
		  $collection=$this->searchWord( $collection, $word );
		 
		 	 $collection ->setPageSize($mageasia_tags_detail_size) ;
        	 $collection ->setCurPage($page);
			 $collection ->setOrder("created_at","desc");
			 
			 
		#  echo $collection->getSelect();
		 
		 
		 
		$this->setData('productitem', $collection);
		$this->setData('lbtags', $tagCollection);
      
		 
        return $this->getData('lbtags');
        
    }
	public function getProductListing()
	{
		
		return $this->getData("productitem");	
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
        	
        /*	$collection->setStore(Mage::app()->getStore())
	            ->addMinimalPrice()
	            ->addFinalPrice()
	            ->addTaxPercents()
	            ->addStoreFilter()
	            ->addUrlRewrite();
	        //Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
	        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);
            if (!$this->_tagsProducts) {
            	$collection->getSelect()->order('rand()')->limit(36);
            } else {
            	$collection->getSelect()->limit(36);
            }	*/
		
	}
	
	public function getPages()
	{ 
	
			$currentPage = (int)$this->getRequest()->getParam('identifier_page');
			 $identifier_code = $this->getRequest()->getParam('identifier_code');
	 
			if($this->idlist!=null){
			 $collectionpro = Mage::getResourceModel('catalog/product_collection');
		   $collection = $this->_addProductAttributesAndPrices($collectionpro);
		 $collection ->addAttributeToFilter('entity_id', array('in' =>$this->idlist)) 
		 ;
			
	
			if(!$currentPage)
			{
				$currentPage = 1;
			}
			
			$route = Mage::helper('lbtags')->getRoute();
			$totalpage=count($collection);
			$this->pagetotal=$pages = ceil(count($collection) / (int)$this->mageasia_tags_detail_size);
			 
			$links = "<div  >";
			$links = $links .  ' <span class="pagetotal" > Total:'.$totalpage.'  Pager:'.$currentPage."/".$pages.'</span>';
			if ($currentPage > 1)
			{
				$links = $links . '<a href="' . Mage::helper("lbtags")->makeTagsViewPageUrl( $identifier_code ,(($currentPage - 1))) . '" > < <   </a> ';
			}else{
				$links = $links . '<a href="#" > < <   </a> ';
			}
				for($i=1;$i<=$pages;$i++)
				{
					$links.='<a href="' . Mage::helper("lbtags")->makeTagsViewPageUrl( $identifier_code ,$i) . '" > '.$i.'   </a>';	
				}
			
			if ($currentPage < $pages)
			{
				$links = $links .  ' <a href="' .  Mage::helper("lbtags")->makeTagsViewPageUrl($identifier_code , (($currentPage + 1))) . '" > > ></a>';
			}else{
					$links = $links .  ' <a href="#" > > ></a>';
			}
			return  $links.'</div>';
			
			}
		 
	}
	
	
}