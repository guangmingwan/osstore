 <?php
 class Ecom_ProductPromotion_IndexController extends Mage_Core_Controller_Front_Action {

    const XML_PATH_CRON_ENABLED  = 'productpromotion/cron/enabled';
    
	public function preDispatch() {
        parent::preDispatch();
        if( !Mage::getStoreConfigFlag(self::XML_PATH_CRON_ENABLED) ) {
            //$this->norouteAction();
        }
    }
 
    public function indexAction() {
		   $website = Mage::app()->getWebsite();
           $this->loadLayout();
		   
		   $block_new = $this->getLayout()->createBlock('productpromotion/email_productnew');
		   $block_new->setWebsite( $website);
		   
		   //echo $block_new->toHtml();
		   $this->getLayout()->getBlock('content')->append($block_new);

		   $block_special = $this->getLayout()->createBlock('productpromotion/email_productspecial');
		   $block_special->setWebsite( $website);
		   //echo $block_special->toHtml();
		   $this->getLayout()->getBlock('content')->append($block_special);

		   $this->renderLayout();
   }

    public function sendAction()  {
		$object = new Varien_Object();
        $observer = Mage::getSingleton('productpromotion/observer');
        $observer->process($object);
    }
} 