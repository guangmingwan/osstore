<?php
class Ecom_ProductPromotion_Model_Observer {

    const XML_PATH_CRON_ENABLED      = 'productpromotion/cron/enabled';


    const XML_PATH_ERROR_TEMPLATE   = 'productpromotion/email/error_email_template';
    const XML_PATH_ERROR_IDENTITY   = 'productpromotion/email/error_email_identity';
    const XML_PATH_ERROR_RECIPIENT  = 'productpromotion/email/error_email_recipient';
    
    /**
     * Website collection array
     *
     * @var array
     */
    protected $_websites;

    /**
     * Warning (exception) errors array
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Retrieve website collection array
     *
     * @return array
     */
    protected function _getWebsites() {
        if (is_null($this->_websites)) {
            try {
                $this->_websites = Mage::app()->getWebsites();
            }
            catch (Exception $e) {
                $this->_errors[] = $e->getMessage();
            }
        }
        return $this->_websites;
    }


    public function process() {
  
        $email = Mage::getModel('productpromotion/email');
 
        foreach ($this->_getWebsites() as $website) {
            /* @var $website Mage_Core_Model_Website */

            if (!$website->getDefaultGroup() || !$website->getDefaultGroup()->getDefaultStore()) {
                continue;
            }
            if (!Mage::getStoreConfig(
                self::XML_PATH_CRON_ENABLED,
                $website->getDefaultGroup()->getDefaultStore()->getId()
            )) {
                continue;
            }

		   $productNewBlock = Mage::helper('productpromotion')->createBlock('productpromotion/email_productnew');
		   $productSpecialBlock = Mage::helper('productpromotion')->createBlock('productpromotion/email_productspecial');

			$productNewBlock->setWebsite( $website);
			$productSpecialBlock->setWebsite( $website);
           $email_content = $productNewBlock->toHtml() . $productSpecialBlock->toHtml();
           $email->setWebsite($website);
		   $email->setEmailContent($email_content);
		   $customers = Mage::getResourceModel('customer/customer_collection')
			 ->addNameToSelect()
			 ->addAttributeToSelect('email')
             ->addAttributeToSelect('created_at')
             ->addAttributeToSelect('group_id')
			 ->addAttributeToFilter('website_id', $website->getId());
            foreach ($customers as $customer) {
				$email->setCustomer($customer);
				
				try {
					$email->send();
				}
				catch (Exception $e) {
					$this->_errors[] = $e->getMessage();
				}
            }
        }
		$this->_sendErrorEmail();
        return $this;
    }


    protected function _sendErrorEmail()
    {
        if (count($this->_errors)) {
            if (!Mage::getStoreConfig(self::XML_PATH_ERROR_TEMPLATE)) {
                return $this;
            }

            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);

            $emailTemplate = Mage::getModel('core/email_template');
            /* @var $emailTemplate Mage_Core_Model_Email_Template */
            $emailTemplate->setDesignConfig(array('area'  => 'backend'))
                ->sendTransactional(
                    Mage::getStoreConfig(self::XML_PATH_ERROR_TEMPLATE),
                    Mage::getStoreConfig(self::XML_PATH_ERROR_IDENTITY),
                    Mage::getStoreConfig(self::XML_PATH_ERROR_RECIPIENT),
                    null,
                    array('warnings' => join("\n", $this->_errors))
                );

            $translate->setTranslateInline(true);
            $this->_errors[] = array();
        }
        return $this;
    }


}
