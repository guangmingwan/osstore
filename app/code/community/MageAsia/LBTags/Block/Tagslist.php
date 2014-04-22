<?php
class MageAsia_LBTags_Block_Tagslist extends Mage_Core_Block_Template
{
		public function _prepareLayout()
    {
		$code=trim($this->getRequest()->getParam('identifier_code'));
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
		 
		if(trim($this->getRequest()->getParam('identifier_code'))){
			$breadcrumbs->addCrumb('tagscodename', 
			
			array('label'=> trim($this->getRequest()->getParam('identifier_code')), 
					'title'=>Mage::helper('lbtags')->__('Go to Tags '.$code.' Page'), 
					 )
			);
		}
		
		return parent::_prepareLayout();
    }
	 protected $_tagslimit = 20;
	 protected $_pagesize=30;
	 protected $_perpage=46;
	 public function setSize($size)
	 {
		 	$this->_tagslimit=$size;
			return $this; 
	 }
 	  public function getProductsCount(){
		  	return $this->_tagslimit;
		  }
 	  public function getLatestTags(){
     
		
	 	$homepagetags=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_polulartags_home_size');
		 
			$items = Mage::getModel('lbtags/tags')->getCollection() 
			 ->addFieldToFilter('status', array('eq'=>1)) 
				 ->setOrder('tags_id', 'desc')
          	  ->setPageSize($homepagetags)
          	  ->setCurPage(1)
			; 
			 
			 //$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
			 return $items;
			
	}
	
	
	
	 public function getTagsList()
   	{	
		$tags = Mage::getSingleton('lbtags/tags');
			 $page = (int)$this->getRequest()->getParam('identifier_page');
			$identifier_code =  trim($this->getRequest()->getParam('identifier_code'));
			 $arritem=array('attribute'=>'name', 'like'=>''.$identifier_code.'%');
			 
			  	$mageasia_tags_list_size=Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_list_size');
				
				
			$tags = Mage::getModel('lbtags/tags')->getCollection()
		 
			->addTagsNameFilter($identifier_code)
			 	 
			->setOrder('created_time', 'desc') 
	 
			 ->setPageSize($mageasia_tags_list_size) 
        	 ->setCurPage($page);
				 
		 
			
			foreach ($tags as $item) 
			{
				 
			 
				 
			 
				//$item->setCats($catUrls);
			}
			$this->setData('tags', $tags);
			return $this->getData('tags');
	 
    }
	
	public function getPages()
	{ 
	
			$currentPage = (int)$this->getRequest()->getParam('identifier_page');
		 $identifier_code = $this->getRequest()->getParam('identifier_code');
			$collection = Mage::getModel('lbtags/tags')->getCollection()
		//	->addStoreFilter(Mage::app()->getStore()->getId())
			->setOrder('created_time ', 'desc')
			
			 ->addTagsNameFilter($identifier_code)
			 ;
			
	
			if(!$currentPage)
			{
				$currentPage = 1;
			}
			
			$route = Mage::helper('lbtags')->getRoute();
			
			$pages = ceil(count($collection) / (int)(Mage::getStoreConfig('magasialbtags/tagsetting/mageasia_tags_list_size')));
			 $totalpage=count($collection);
			$links = "<div  >";
			$links = $links .  ' <span class="pagetotal" >  Total:'.$totalpage.'  Pager:'.$currentPage."/".$pages.'</span>';
			if ($currentPage > 1)
			{
				$links = $links . '<a href="' . Mage::helper("lbtags")->makeTagsPageUrl( $identifier_code ,(($currentPage - 1))) . '" > < <   </a> ';
			}else{
				$links = $links . '<a href="#" > < <   </a> ';
			}
				for($i=1;$i<=$pages;$i++)
				{
					$links.='<a href="' . Mage::helper("lbtags")->makeTagsPageUrl( $identifier_code ,$i) . '" > '.$i.'   </a>';	
				}
			
			if ($currentPage < $pages)
			{
				$links = $links .  ' <a href="' .  Mage::helper("lbtags")->makeTagsPageUrl($identifier_code , (($currentPage + 1))) . '" > > ></a>';
			}else{
					$links = $links .  ' <a href="#" > > ></a>';
			}
			echo $links.'</div>';
		 
	}
	
    
     
}