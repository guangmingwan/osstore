<?php

class MageAsia_LBTags_Adminhtml_TagsController extends Mage_Adminhtml_Controller_Action
{
	public function preDispatch()
    {
        parent::preDispatch();
    }
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('lbtags/tags')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Tags Manager'), Mage::helper('adminhtml')->__('Tags Manager'));
		
		return $this;
	}   
  	public function tagsmatchproductAction(){
		 
		$this->_initAction()
				->renderLayout();
		
	}
	public function runtagsAction(){
		
		$this->_initAction()
				->renderLayout();
		
	}
	public function runtagsmatchproductAction(){
		$page=$this->getRequest()->getParam("page");
		$form_key=$this->getRequest()->getParam("form_key");
		$catid=$this->getRequest()->getParam("catid");
		
		$tagids=$this->getRequest()->getParam("tagids");
		$tagidlist=preg_split("/,/",$tagids);
		
		$tags_limit=$this->getRequest()->getParam("tags_limit");
		$max_product=$this->getRequest()->getParam("max_product")==""?20:$this->getRequest()->getParam("max_product");
		$product_tags_limit=$this->getRequest()->getParam("product_tags_limit");
		$min_3words_length=$this->getRequest()->getParam("min_3words_length");
		$min_3words_phrase_length=$this->getRequest()->getParam("min_3words_phrase_length");
		$min_3words_phrase_occur=$this->getRequest()->getParam("min_3words_phrase_occur");
		
		$min_2words_length=$this->getRequest()->getParam("min_2words_length");
		$min_2words_phrase_length=$this->getRequest()->getParam("min_2words_phrase_length");
		$min_2words_phrase_occur=$this->getRequest()->getParam("min_2words_phrase_occur");
		 
		
		$alltags = Mage::getModel('lbtags/tags')->getCollection() 
		
		//$alltags-> addFieldToFilter('tagcount', array('lteq' => $product_tags_limit))
		
	//	;
		->setOrder("tags_id",'desc') ;
		
		//
		if(count($tagidlist)>0){
			$alltags->addFieldToFilter('tags_id',array('in'=>$tagidlist));
		}else{
			
		}
		
		 
		$includepath= dirname(dirname(dirname(__FILE__))).DS."lib".DS.'Autokeyword.php';
		include($includepath);
	
		if($page==""){$page=1;}
		if($_GET['total']==""){
		$total=count($alltags);
		}else{
		$total	=$_GET['total'];
		}
		$totalpage=ceil($total/$tags_limit);
		 
		 if($page>$totalpage){ 
				  Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lbtags')->__('Save successfully'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
	 				die("Complete,Please <a href='".$this->getURL('*/*/')."'>click here</a>.");
		 
		 }else{
					echo "Total: ".$totalpage."/".$page."<br />" ;
					echo "Max Product to tags: ".$max_product."<br />" ;
					
					$collection = Mage::getModel('lbtags/tags') ->getCollection() 
					//	->addFieldToFilter('tagcount', array('lteq' => $product_tags_limit))
						->setPageSize($tags_limit)->setOrder("tags_id",'desc') 
        				->setCurPage($page);
					 
					 if(count($tagidlist)>0){
						$collection->addFieldToFilter('tags_id',array('in'=>$tagidlist)) ->setCurPage($page);
					}
				//	die(   $collection->getSelect() );			 
					 
				
					 foreach($collection as $tag)
					 {
						 
						 $countx = Mage::getModel('lbtags/tags_relation')->TagsReCount($tag->getTagsId());
						 echo "--Count:".$countx."<br />";
						 if($tag->getTagcount()>=$product_tags_limit){
							 	continue;	
						 }
						 
						/* $params=array();
						$params['content'] =$tag->getName(); //page content
						 
						$params['min_word_length'] = 2;  //minimum length of single words
						$params['min_word_occur'] = 0;  //minimum occur of single words
					
						$keyword = new autokeyword($params, "utf-8");//
						$worditemlist = trim($keyword->parse_words());
					
							if($worditemlist==""){	$worditemlist =$tag->getName();} else{
								
							}*/
							if( $tag->getName() ==""){
										continue;	
									}
					 
						//split(" ",trim($tag->getName()));
						
						echo $tag->getName();
					 	echo  "<br />";	
					 
						$worditem=array();
						$splititem= preg_split("/[.,;!\s']\s*/", $tag->getName());
						foreach($splititem as $itemkey)
						{
							if(strlen($itemkey)>3){
							$worditem[]=$itemkey;
							}
						}
						
						 
						if(count($worditem)>0){
							
							// $worditem= array_rand($worditem,count($worditem));
							$shuffle=array_unique($worditem);
							
							 shuffle($shuffle);
							 $worditem=$shuffle;
						  
							foreach($worditem as $key){
								if($key!=""){
								  	echo "Keyword:".$key."<br />";
									  $productitem = Mage::getModel('lbtags/tags')->searchTagtoProduct($tag,trim($key),$max_product,$catid,$countx);
									if($productitem==false){
										continue;	
									}
								
								}
							}
							 
						}else{
						  $productitem = Mage::getModel('lbtags/tags')->searchTagtoProduct($tag,trim($tag->getName()),$max_product,$catid,$countx);
						}
						
					   $countx = Mage::getModel('lbtags/tags_relation')->TagsReCount($tag->getTagsId());
					 
							 
							 
					 }//end foreach
					 
					 
				   echo "<script> function locatnnewurl(){ location.href='??&product_tags_limit=".$product_tags_limit."&form_key=".$form_key."&tags_limit=".$tags_limit."&page=".($page+1)."&catid=".$catid."&tagids=".$tagids."&max_product=".$max_product."&totalTags=".$total."'} setTimeout('locatnnewurl()',1000);</script>";
		 
					 
			 
		 }
		 
		  
		
	}
	public function runtagsbeginAction(){
		
		$includepath= dirname(dirname(dirname(__FILE__))).DS."lib".DS.'Autokeyword.php';
		include($includepath);
		// 	$includepath= dirname(dirname(dirname(__FILE__))).DS."lib".DS.'WordSegment.class.php';
	//	include($includepath);
		$page=$this->getRequest()->getParam("page");
		$form_key=$this->getRequest()->getParam("form_key");
		$product_limit=$this->getRequest()->getParam("product_limit");
		$page=$this->getRequest()->getParam("page");
		$min_3words_length=$this->getRequest()->getParam("min_3words_length");
		$min_3words_phrase_length=$this->getRequest()->getParam("min_3words_phrase_length");
		$min_3words_phrase_occur=$this->getRequest()->getParam("min_3words_phrase_occur");
		
		$min_2words_length=$this->getRequest()->getParam("min_2words_length");
		$min_2words_phrase_length=$this->getRequest()->getParam("min_2words_phrase_length");
		$min_2words_phrase_occur=$this->getRequest()->getParam("min_2words_phrase_occur");
		
		
		if($page==""){$page=1;}
		$mainproduct =     Mage::getResourceModel('catalog/product_collection');
        $mainproduct ->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) ;
		$total=$mainproduct->getSize();
		 
		$totalpage=ceil($total/$product_limit);
		 if($page>$totalpage){ 
		 
		 
					 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lbtags')->__('Save successfully'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
	 				die("Complete,Please <a href='".$this->getURL('*/*/')."'>click here</a>.");
		 
		 
		  }
		$collection =  Mage::getResourceModel('catalog/product_collection') 
        ->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)  
		 
		  ->setPageSize($product_limit) 
        	 ->setCurPage($page);
			//echo  $collection->getSelect();
			foreach($collection as $product){ 
				$productModule=Mage::getModel("catalog/product")->load($product->getId());
				
				$params['content'] =$productModule->getName(); //page content
				//set the length of keywords you like
				$params['min_word_length'] = 5;  //minimum length of single words
				$params['min_word_occur'] = 1;  //minimum occur of single words
				
				$params['min_2words_length'] = $min_2words_length;  //minimum length of words for 2 word phrases
				$params['min_2words_phrase_length'] = $min_2words_phrase_length; //minimum length of 2 word phrases
				$params['min_2words_phrase_occur'] = $min_2words_phrase_occur; //minimum occur of 2 words phrase
				
				$params['min_3words_length'] = $min_3words_length;  //minimum length of words for 3 word phrases
				$params['min_3words_phrase_length'] = $min_3words_phrase_length; //minimum length of 3 word phrases
				$params['min_3words_phrase_occur'] = $min_3words_phrase_occur; //minimum occur of 3 words phrase
				
				$keyword = new autokeyword($params, "utf-8");
				echo "<br /><br />Product Name: ".$productModule->getName()."<br/>";
				// echo $keyword->parse_2words()."<br /><br />";
				 
				// echo $keyword->parse_3words()."<br /><br />";
				 $word=$keyword->parse_3words().",".$keyword->parse_2words();
				 
				 $model = Mage::getModel('lbtags/tags')->addWrodtoTags($word,$product);
				 
			}
				 
	 	echo "<script> function locatnnewurl(){ location.href='??form_key=".$form_key."&product_limit=".$product_limit."&min_3words_length=".$min_3words_length."&min_3words_phrase_length=".$min_3words_phrase_length."&min_3words_phrase_occur=".$min_3words_phrase_occur."&min_2words_length=".$min_2words_length."&min_2words_phrase_length=".$min_2words_phrase_length."&min_2words_phrase_occur=".$min_2words_phrase_occur."&page=".($page+1)."'} setTimeout('locatnnewurl()',1000);</script>";
		die("");
		 
	}
	
	
	 
	public function indexAction() {
		 
		$this->_initAction()
			->renderLayout();
	}
	public function importdataAction(){
		
		 
	if ($data = $this->getRequest()->getPost()) {
		 
			if(isset($_FILES['fileupload']['name']) && $_FILES['fileupload']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('fileupload');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('csv'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('var') . DS.'import' ;
					$uploader->save($path, $_FILES['fileupload']['name'] );
					$fileurl=$path."".DS. $_FILES['fileupload']['name'];
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['fileupload'] = $_FILES['fileupload']['name'];
			}
			 
			
			
			
			
			
			
			
	}
	
	if($fileurl!=""){
		$includepath= dirname(dirname(dirname(__FILE__))).DS."lib".DS.'CsvProgress.php';
		include($includepath);
		 
		$csv =new CsvProgress;
		$csv->load($fileurl);
		$datalist=($csv->connect());
		$header=$csv->getHeaders();	
		$mess=0;
		foreach($datalist as   $vitem )
		{
			$tagsdata=array();
			
			
			 
				foreach($header as $k=>$v){
					foreach($vitem as $kx=>$vx){
						switch($kx){
							case "name";	
								$tagsdata['name']=$vx;		
							break;
							case "meta_title";	
								$tagsdata['meta_title']=$vx;		
							break;
							case "meta_keyword";	
								$tagsdata['meta_keyword']=$vx;		
							break;
							case "meta_description";	
								$tagsdata['meta_description']=$vx;		
							break;
							case "Productids";	
								$tagsdata['productid']=$vx;		
							break;
							case "meta_keywords";	
								$tagsdata['meta_keywords']=$vx;		
							break;
							case "comments";	
								$tagsdata['comments']=$vx;		
							break;
							
							case "tags_id";	
								$tagsdata['tags_id']=$vx;		
							break;
						}
					
						
					}
				}
				$liststr=explode(" ",$tagsdata['name']);
				#if(count($liststr)<2){ continue; }
				$Identifier=Mage::helper("lbtags")->FixIdentifier($tagsdata['name']);
				$tagsdata['identifier']=$Identifier;
				$tagsdata['status']=1;
				$model = Mage::getModel('lbtags/tags');		
			  	if($tagsdata['tags_id']>0){
					 $model->load($tagsdata['tags_id']);
					
					 $message="Duplicate item  for '".$tagsdata['name']."' ";
					 Mage::getSingleton('adminhtml/session')->addSuccess($message);
					
				}else{
					
					$modeld=$model->load($Identifier,'identifier');
					if(!$modeld){
							
					}else{
							 $message="Duplicate item  for '".$tagsdata['name']."' ";
							 Mage::getSingleton('adminhtml/session')->addSuccess($message);
						
					}
				
				}
				if($modelData){}
			 	//	->addFieldToFilter("identifier",$Identifier);
				 /*if(count($modelData)>0){
					 	$mess++;
					 $message="Duplicate item  for '".$tagsdata['name']."' ";
					 Mage::getSingleton('adminhtml/session')->addError($message);
					continue;
				 }*/
			
				$model->setData($tagsdata);
			 
				$productIds=preg_split('/,/',$tagsdata['productid']);
				
				
				try {
					if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
						$model->setCreatedTime(now())
							->setUpdateTime(now());
					} else {
						$model->setUpdateTime(now());
					}	
					
					$model->save();
					
					if(count($productIds)>0)
					{
						$tagRelationModel = Mage::getModel('lbtags/tags_relation');
						 
						$tagRelationModel->addRelations($model, $productIds);	
					}
					
					
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lbtags')->__('\''.$tagsdata['name'].'\' was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
	
				 
					
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					 
				 	$mess++;
				}
				
		 
			
		}
	}
	$this->_redirect('*/*/import');
	return;
	 
		}
		
	public function saveproductimportdataAction(){
		
		$fileurl=""; 
		if ($data = $this->getRequest()->getPost()) {
			 
				if(isset($_FILES['fileupload']['name']) && $_FILES['fileupload']['name'] != '') {
					try {	
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('fileupload');
						
						// Any extention would work
						$uploader->setAllowedExtensions(array('csv'));
						$uploader->setAllowRenameFiles(false);
						
						// Set the file upload mode 
						// false -> get the file directly in the specified folder
						// true -> get the file in the product like folders 
						//	(file.jpg will go in something like /media/f/i/file.jpg)
						$uploader->setFilesDispersion(false);
								
						// We set media as the upload dir
						$path = Mage::getBaseDir('var') . DS.'import' ;
						$uploader->save($path, $_FILES['fileupload']['name'] );
						$fileurl=$path."".DS. $_FILES['fileupload']['name'];
						
					} catch (Exception $e) {
				  
					}
				
					//this way the name is saved in DB
					$data['fileupload'] = $_FILES['fileupload']['name'];
				}
				 
				
				
				
				
				
				
				
		}
	
	if($fileurl!=""){
		$includepath= dirname(dirname(dirname(__FILE__))).DS."lib".DS.'CsvProgress.php';
		include($includepath);
		 
		$csv =new CsvProgress;
		$csv->load($fileurl);
		$datalist=($csv->connect());
		$header=$csv->getHeaders();	
		$mess=0;
		foreach($datalist as $vvitem)
		{
				
		}
		foreach($datalist as   $vitem )
		{
			$tagsdata=array();
			
			
			 
				foreach($header as $k=>$v){
					foreach($vitem as $kx=>$vx){
						switch($kx){
							case "Sku";	
								$tagsdata['sku']=$vx;		
							break;
							 
							case "Tagid";	
								$tagsdata['tagids']=$vx;		
							break;
						}
					
						
					}
				}
				
				$product=Mage::getModel("catalog/product")->loadByAttribute("sku",$tagsdata['sku']);
				#echo $tagsdata['sku']." - ";
				 
				$productname=$product->getName();
				
				$productid=$product->getId();
				$productidarr=array($productid);
				$tagids=explode(",",$tagsdata['tagids']);
			 
				if(count($tagids)>0){
				
					foreach($tagids as $k=>$tag)
					{ 
					
						$model = Mage::getModel('lbtags/tags')->load($tag);	
						if($model&& $tag){
						$modelRelation = Mage::getModel('lbtags/relation')->getCollection()
											->addFieldToFilter("tags_id",$tag)
											->addFieldToFilter("product_id",$productid)
											;
											
									#	 print_r($model);
								#	echo $productid;
									 #	 	exit();
						 if(count($modelRelation)==0){
								
							 try {
							
						
							$tagRelationModel = Mage::getModel('lbtags/tags_relation');
							
							#echo $tag." - ".print_r($productidarr).'-<br />';
							$tagRelationModel->addRelations($model, $productidarr,true);	
							
					Mage::getSingleton('adminhtml/session')->addSuccess(  Mage::helper('lbtags')->__('\'The '.$productname.' add to '.$model->getName().'\' Tag was successfully saved'));
							
						  
						  Mage::getSingleton('adminhtml/session')->setFormData(false);
						 
						  Mage::getModel('lbtags/tags_relation')->TagsReCount($tag);
					
							} catch (Exception $e) {
								Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
							#	Mage::getSingleton('adminhtml/session')->setFormData($data);
								 
								#$mess++;
							}
							
						 }else{
							 $message="Duplicate item  for '".$model->getName()."' ";
							 Mage::getSingleton('adminhtml/session')->addError($message);
					 
						 }
						 
						}
					}
					
				}
				
				
				
			#	die(print_r($tagsdata));
				  
		 
			
		}
	}
	#echo ("<br />---");
 	$this->_redirect('*/*/productimport');
	return;
 
	}
	public function retagcountAction()
	{
		 $tagsIds = $this->getRequest()->getParam('lbtags');	
		 if(count( $tagsIds)>0){
				
				foreach( $tagsIds as $item){
					
					 
					  Mage::getModel('lbtags/tags_relation')->TagsReCount($item);
					  
					  
				}
		
					
					Mage::getSingleton('adminhtml/session')->addSuccess(  Mage::helper('lbtags')->__('\'The '.count($tagsIds).' tags was update successfully saved'));
					
						 
		 }else{
						  $message="Please select tags.";
							 Mage::getSingleton('adminhtml/session')->addError($message);
		 }
		 
		$this->_redirect('*/*/');
	}
		 
		
	public function productimportAction() {
	 	  
		$this->_initAction()
			->renderLayout();
	}
	public function importAction() {
	 	  
		$this->_initAction()
			->renderLayout();
	}
	    public function assignedGridOnlyAction()
    {
		 
		$this->_initTag();
        $this->loadLayout()
			->renderLayout();
	 
    }
	
	 protected function _initTag()
    {
        $model = Mage::getModel('lbtags/tags');
        $storeId = $this->getRequest()->getParam('store');
        $model->setStoreId($storeId);

        if (($id = $this->getRequest()->getParam('id'))) {
            
            $model->load($id);
            $model->setStoreId($storeId);

            if (!$model->getId()) {
                return false;
            }
        }

        Mage::register('tags_data', $model);
        return $model;
    }

	

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('lbtags/tags')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('tags_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('lbtags/tags');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			 
			$this->_addContent($this->getLayout()->createBlock('lbtags/adminhtml_tags_edit'))
				->_addLeft($this->getLayout()->createBlock('lbtags/adminhtml_tags_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(  Mage::helper('lbtags')->__('Item does not exist')  );
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
	 	$this->_forward('edit');
	}
 	 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$tagsdata=array();
			$tagsdata=$data['tags'];
			$tagsdata['tags_id']=$data['tags_id'];
		 	
			if($tagsdata['identifier']!=""){
				
	  		 $Identifier=Mage::helper("lbtags")->FixIdentifier($tagsdata['identifier']);
			 $tagsdata['identifier']=$Identifier;
	  		 
			}else{
				
				$Identifier=Mage::helper("lbtags")->FixIdentifier($tagsdata['name']);
				 $tagsdata['identifier']=$Identifier;
					
			}
			
			 
			$model = Mage::getModel('lbtags/tags');		
			 $modelData=$model->getCollection()
			 ->addFieldToFilter("identifier",$Identifier);
			 if($this->getRequest()->getParam('id')>0){
				 $modelData->addFieldToFilter("tags_id",array("neq"=>$this->getRequest()->getParam('id')));
			 }
		 
			if(count($modelData)>0){
			    Mage::getSingleton('adminhtml/session')->addError("Duplicate identifier field:".$Identifier);
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return ;
			}
			///$data['name']=$this->getRequest()->getParam('tagsname');
			 
			//die(":");
			
			$model->setData($tagsdata)
				->setId($this->getRequest()->getParam('id'));
			
			 if (isset($data['tags_assigned_products'])) {
                $productIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['tags_assigned_products']);
                $tagRelationModel = Mage::getModel('lbtags/tags_relation');
				 
                $tagRelationModel->addRelations($model, $productIds);
            }
	    
						$model->setTagcount(count($productIds));
						 
						
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lbtags')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lbtags')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			
				$model = Mage::getModel('lbtags/tags');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					$tagRelationModel = Mage::getModel('lbtags/tags_relation');
					$tagRelationModel->deleteRelations($this->getRequest()->getParam('id'));
				 
			try {		 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $tagsIds = $this->getRequest()->getParam('lbtags');
        if(!is_array($tagsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($tagsIds as $tagsId) {
                    $tags = Mage::getModel('lbtags/tags')->load($tagsId);
                    $tags->delete();
					$tagRelationModel = Mage::getModel('lbtags/tags_relation');
					$tagRelationModel->deleteRelations($tagsId);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($tagsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $tagsIds = $this->getRequest()->getParam('lbtags');
        if(!is_array($tagsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($tagsIds as $tagsId) {
                    $tags = Mage::getSingleton('lbtags/tags')
                        ->load($tagsId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($tagsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportTags2ProductCsvAction()
    {
        $fileName   = 'tags_'.date("ymdhis").'.csv';
       # $content    = $this->getLayout()->createBlock('lbtags/adminhtml_export_products_grid')
         #   ->getCsv();
		#die("2222");();
		  $collection = Mage::getModel('catalog/product')->getCollection();
		  $tagsIds = $this->getRequest()->getParam('lbtags');
		 #print_r(  $tagsIds  );
		 $content='Sku,Tagid'."\n";
		 
		 foreach($collection as $item)
		 {
			 $v=$item->getSku();
			 $id=$item->getId();
			 $tagids = Mage::getModel('lbtags/tags_relation') ->getTagsIdList($id);
			# print_r($productids);
				$content.="$v".",".'"'.implode(",",$tagids).'"'."\n";
					 
		 }
			 
		 
		
        $this->_sendUploadResponse($fileName, $content);
    }
	
	 public function exportAllTagsCsvAction()
    {
        $fileName   = 'tags_all_'.date("ymdhis").'.csv';
        $content    = $this->getLayout()->createBlock('lbtags/adminhtml_tags_gridtagall')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
	
	 public function exportProductCsvAction()
    {
      #  $fileName   = 'products.csv';
      #  $content    = $this->getLayout()->createBlock('lbtags/adminhtml_export_products_grid')
      #      ->getCsv();

      #  $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'tags.xml';
        $content    = $this->getLayout()->createBlock('lbtags/adminhtml_tags_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
	
	 public function assignedAction()
    {
        $this->_title($this->__('lbtags'))->_title($this->__('Assigned'));

        $this->_initTag();
        $this->loadLayout();
        $this->renderLayout();
    }
	
	 /*public function importAction()
    {
        $this->_title($this->__('lbtags'))->_title($this->__('Import'));

        
        $this->loadLayout()
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Tags Import'), Mage::helper('adminhtml')->__('Tags Import'));
			
			
			$this->_setActiveMenu('lbtags/tags');

			//$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			//$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

		//	$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

		//	$this->_addContent($this->getLayout()->createBlock('lbtags/adminhtml_import'))
				//->_addLeft($this->getLayout()->createBlock('lbtags/adminhtml_LBTags_edit_tabs'));
		;
			
			
        $this->renderLayout();
    }*/

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}