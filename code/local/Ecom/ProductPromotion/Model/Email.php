<?php
class Ecom_ProductPromotion_Model_Email extends Mage_Core_Model_Abstract {
    const XML_PATH_EMAIL_TEMPLATE = 'productpromotion/email/email_template';
    const XML_PATH_EMAIL_IDENTITY       = 'productpromotion/email/email_identity';

	/**
     * Website Model
     *
     * @var Mage_Core_Model_Website
     */
    protected $_website;

    /**
     * Customer model
     *
     * @var Mage_Customer_Model_Customer
     */
    protected $_customer;
   
    /**
     * Type
     *
     * @var string
     */
	protected $_emailContent;

    public function setWebsite(Mage_Core_Model_Website $website) {
        $this->_website = $website;
        return $this;
    }

	public function setCustomer(Mage_Customer_Model_Customer $customer)  {
        $this->_customer = $customer;
        return $this;
    }

    public function setEmailContent($emailContent) {
        $this->_emailContent = $emailContent;
    }

	public function clean() {
        $this->_customer      = null;
        return $this;
    }

	 /**
     * Send customer email
     *
     * @return bool
     */
    public function send()
    {
      
		if (is_null($this->_website) || is_null($this->_customer)) {
            return false;
        }

        if (!$this->_website->getDefaultGroup() || !$this->_website->getDefaultGroup()->getDefaultStore()) {
            return false;
        }

        $store      = $this->_website->getDefaultStore();
        $storeId    = $store->getId();

        if (!Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId)) {
            return false;
        } 

        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

        $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
       
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
		
        Mage::getModel('core/email_template')
            ->setDesignConfig(array(
                'area'  => 'frontend',
                'store' => $storeId
            ))->sendTransactional(
                $templateId,
                Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId),
                $this->_customer->getEmail(),
                $this->_customer->getName(),
                array(
                    'customerName'  => $this->_customer->getName(),
                    'productGrid'   => $this->_emailContent
                )
            );

        return true;
    }
}