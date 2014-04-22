<?php
 

class MageAsia_LBTags_Controller_Router extends  Mage_Core_Controller_Varien_Router_Abstract
{
	const __MODULENAME="tags";
	protected $_request=null;
	protected $_ishtml=false;
	
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
 		$enable=Mage::helper("lbtags")->isEnablePlguin();
        if($enable==1){
			$tags = new MageAsia_LBTags_Controller_Router();		
			$front->addRouter('tags', $tags);
		}
			
		 
		 
    }
	
	public function getRequest($request)
	{
			return $this->_request;
	}

    public function match(Zend_Controller_Request_Http $request)
    {
		
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
	 	$this->_request=$request;
		$route = Mage::helper('lbtags')->getRoute();
		 
		$identifier = $request->getPathInfo();
#	 echo $identifier;
		$sroute=substr(str_replace("/", "",$identifier), 0, strlen($route));
		 
		 
		if (substr(str_replace("/", "",$identifier), 0, strlen($route)) != $route)
		{
			return false;
		}
		
		#echo $identifier;
		#exit();
        $identifier = substr_replace( $request->getPathInfo(),'', 0, strlen("/" . $route. "/") );
		$this->_ishtml=strpos(strtolower($identifier),".html")===false?false:true;
		   
		$identifier = str_replace('.html', '', $identifier);
		$identifier = str_replace('.htm', '', $identifier);		
		
		$strArray=explode("/",$identifier);
		 $request->setModuleName('tags')
					->setControllerName('index') ;
				
		 
			$strbase=$strArray[0];
			$strlenkeyword=strlen($strArray[0]);
			
		#	print_r($strArray);
			#exit();
			
			switch($strlenkeyword)
			{
				case 0:
					return $this->setIndexRoute($strbase);
				break;
				case 1:
					return $this->setTagListRoute($strbase,$strArray);
				break;
				case 3:
					return $this->setTagListRoute($strbase,$strArray);
				break;
				default :
					return $this->setTagViewRoute($strbase,$strArray);
				break;
					
			}
			 
			return false;
		 
					
					
		 
    }
	
	protected function setIndexRoute($routekeyword){
			$this->getRequest() 
				->setControllerName('index')
				->setActionName('index');
				return true;
	}
	protected function setTagViewRoute($keyword,$strArray){
			// echo $pagename. count($TotalTagscollection);
					$request=$this->getRequest();
					$maxpage=1;
					$tagCollection = Mage::getModel('lbtags/tags')->load($keyword, 'identifier');
					 if($tagCollection->getTagsID()>0){
							if($this->_ishtml==false){
								return false;	
							}
							
					#	$mageasia_tags_detail_size=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_detail_size'); 		 
					#	$productids = Mage::getModel('lbtags/tags_relation') ->getProductById($tagCollection->getTagsId());	
					#	$maxpage=ceil(count($productids)/$mageasia_tags_detail_size);
							
					 }else{
							 return false;  
					 }
					 
					$request 
					->setControllerName('list') 
					->setActionName('show')
					->setParam("q",$tagCollection->getName())
					->setParam("identifier_code",$keyword);
					
					 $query = Mage::helper('catalogsearch')->getQuery();
						/* @var $query Mage_CatalogSearch_Model_Query */
				
						$query->setStoreId(Mage::app()->getStore()->getId());
						 
						if ($query->getQueryText() != '') {
							if (Mage::helper('catalogsearch')->isMinQueryLength()) {
								$query->setId(0)
									->setIsActive(1)
									->setIsProcessed(1);
							}
							else {
								if ($query->getId()) {
									$query->setPopularity($query->getPopularity()+1);
								}
								else {
									$query->setPopularity(1);
								}
				
								if ($query->getRedirect()){
									$query->save();
									$this->getResponse()->setRedirect($query->getRedirect());
									return;
								}
								else {
									$query->prepare();
								}
							}
				
							Mage::helper('catalogsearch')->checkNotes();
					
						  if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
								$query->save();
							}
						}
						 
					
					if(count($strArray)>1){
						$leng=intval($strArray[1]);
						if($leng>1)
						{
							#if($leng>$maxpage){
							#	return false;	
						#	}else{
								$request->setParam("p",$leng);	
								$request->setParam("identifier_page",$leng);	
							
							#}
						}
						
					} 
					return true;
	}
	protected function setTagListRoute($keyword,$strArray){
			
			$this->getRequest()->setActionName('index')
				->setControllerName('list')
				->setParam("identifier_code",$keyword);	
				
				$mageasia_tags_list_size=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_list_size');
				$TotalTagscollection = Mage::getModel('lbtags/tags')->getCollection()->addTagsNameFilter($keyword);
					 
				$mpage=ceil(count($TotalTagscollection)/$mageasia_tags_list_size);
				
				if(count($strArray)>1){
					$leng=intval($strArray[1]);
					if($leng>1)
					{
						if($strArray[1]>$mpage){
							return false;	
						}else{
						
							$this->getRequest()->setParam("identifier_page",$strArray[1]);	
						}
					}
					
				}	
				return true;
	}
	
	
}
