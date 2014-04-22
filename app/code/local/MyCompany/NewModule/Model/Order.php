<?php
class MyCompany_NewModule_Model_Order extends Mage_Sales_Model_Order
{
    /**
     * Sending email with order data
     *
     * @return Mage_Sales_Model_Order
     */
    public function sendNewOrderEmail() {
        parent::sendNewOrderEmail();
	error_log("dbg:MyCompany_NewModule_Model_Order");
        /**
         * Your admin email sending code here. Copy it out of the sendNewOrderEmail
         * function in Sales_Order.
         */
	if($this->getPayment()->getMethod() === Mage_Payment_Model_Method_Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE)
	{
		$this->sendBankTransEmail();
	}
        return $this;
    }
    public function sendBankTransEmail()
    {
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        $storeId  = Mage::app()->getStore()->getId();
        $template ='1';
        $customerName = Mage::getSingleton('customer/session')->getCustomer()->getName();

        $recipient = array(
                'name'  => $customerName,
                'email' => Mage::getSingleton('customer/session')->getCustomer()->getEmail()
            );

        $sender=Mage::getStoreConfig('sales_email/order/identity', $storeId) ;//,使用magento后台配置的发送人 
        $mailTemplate = Mage::getModel('core/email_template')->load($template);
        $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
             ->sendTransactional(
                 $template,
                 $sender,
                 $recipient['email'],
                 $recipient['name'],
                 array( // parameters to email  
             'param1'=> 'abc',
             'param2'=> 'def',
             'param3'=> 'ghi'
       //这里是传进邮件模板里面的变量, 在模板里面用 {{var param1 }}获取，如果传的是对象可以这样使用 {{var object.getId()}}
         )
        );
        //var_dump($recipient);
        //var_dump($sender);
                if (!$mailTemplate->getSentSuccess()) {
                     Mage::throwException($this->__('send email Fail .'));
                }
        $translate->setTranslateInline(true);

     }
}
