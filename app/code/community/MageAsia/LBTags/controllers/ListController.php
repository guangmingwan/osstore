<?php
class MageAsia_LBTags_ListController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	 
			
		$this->loadLayout(); 
		if ($head = $this->getLayout()->getBlock('head')) {
			
			$title=Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagslist_title');
			$keyword=Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagslist_metakeyword');
			$description=Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagslist_metadescription');
				$code=$this->getRequest()->getParam('identifier_code');
				$page=$this->getRequest()->getParam('identifier_page');
				$title=str_replace("%tag%",$code,$title);
				$keyword=str_replace("%tag%",$code,$keyword);
				$description=str_replace("%tag%",$code,$description);
				if($page==""){$page=1;}
				if($page>1){
					 $title=	$title." - Page ".$page;
				}
				
			$head->setTitle($title);
			$head->setKeywords($keyword);
			$head->setDescription($description);
			 
		}     
		$this->renderLayout();
    }
	
	 public function showAction()
    {
    	
    	 
			
		$this->loadLayout(); 
		#print_r($this->getRequest());
		$page =  trim($this->getRequest()->getParam('identifier_page'));
		$identifier_code =  trim($this->getRequest()->getParam('identifier_code'));
		$page=$this->getRequest()->getParam('identifier_page');
		$tagModel = Mage::getModel('lbtags/tags')
	 
	    ->load($identifier_code, 'identifier');
		
		Mage::register("lbtags_item",$tagModel);
		
	//	$tagCollection=  Mage::register('tagsdata', $tagCollection);
 	$tag=$tagModel;
		
		
	if ($head = $this->getLayout()->getBlock('head')) {
			$title=Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagsdetail_title');
			$keyword=Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagsdetail_metakeyword');
			$description=Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_tagsdetail_metadescription');
			
			
			/*	if($page==""){$page=1;}
				if($page>1){
					 $title=	$title." - Page ".$page;
				}*/
			
			$title=str_replace("%title%",$tag->getName(),$title);
			$title=str_replace("%metatitle%",$tag->getMeta_title(),$title);
			$keyword=str_replace("%metakeywords%",$tag->getMeta_keywords(),$keyword);
			$keyword=str_replace("%title%",$tag->getName(),$keyword);
			$keyword=str_replace("%metatitle%",$tag->getMeta_title(),$keyword);
			
			
			$description=str_replace("%title%",$tag->getName(),$description);
			$description=str_replace("%metatitle%",$tag->getMeta_title(),$description);
			$description=str_replace("%metakeywords%",$tag->getMeta_keywords(),$description);
			$description=str_replace("%metadescription%",$tag->getMeta_description(),$description);
			
			
			$pagesuffix="";
			if($page==""){$page=1;}
				if($page>1){
					$pagesuffix=" - Page ".$page;
					 $title=	$title." - Page ".$page;
				}
			
			if($tag->getMeta_title()!=""){
				$head->setTitle(($tag->getMeta_title().$pagesuffix));
			
			}else{
				$head->setTitle(($title));
			}
			if($tag->getMeta_keywords()!=""){
				$head->setKeywords(trim($tag->getMeta_keywords()));
			}else{
				$head->setKeywords(trim($keyword));	
			}
			if($tag->getMeta_description()!=""){
				$head->setDescription(trim($tag->getMeta_description()));
			}else{
				$head->setDescription(trim($description));
			}
			
			 
		} 	/**/
		    
		$this->renderLayout();
    }
	
}