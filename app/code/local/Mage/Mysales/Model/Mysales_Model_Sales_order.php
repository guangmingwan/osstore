 <?php
Mysales_Model_Sales_order extends Mage_Sales_Model_Order {

    /**
     * Sending email with order data
     *
     * @return Mage_Sales_Model_Order
     */
    public function sendNewOrderEmail() {
        parent::sendNewOrderEmail();

        /**
         * Your admin email sending code here. Copy it out of the sendNewOrderEmail
         * function in Sales_Order.
         */

        return $this;
    }
}
