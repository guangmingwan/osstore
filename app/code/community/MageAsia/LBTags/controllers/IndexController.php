<?php
class MageAsia_LBTags_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$this->loadLayout();   
		 
		 
    	if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_home_title'));
			$head->setKeywords(Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_home_metakeyword'));
			$head->setDescription(Mage::getStoreConfig('magasialbtags/tagsseosetting/mageasia_lbtags_home_metadescription'));
			 
		} 
		$this->renderLayout();
    }
	
	/*public function showAction(){
		$this->loadLayout();   
		
		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle("Popular Tags list");//Mage::getStoreConfig('lbtags/config_index/title'));
			$head->setKeywords(Mage::getStoreConfig('lbtags/config_index/keywords'));
			$head->setDescription(Mage::getStoreConfig('lbtags/config_index/description'));
			 
		}   
		$this->renderLayout();
		
	}*/
}