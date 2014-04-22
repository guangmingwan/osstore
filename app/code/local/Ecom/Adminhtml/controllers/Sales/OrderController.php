<?php
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';  
class Ecom_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    public function updateTotalsAction(){
    	try {
	    		$response = false;
	    		$totals = $this->getRequest()->getPost('totals');
	    		$order = $this->_initOrder();
	    		$subtotal = $totals['subtotal'];
	    		$shipping_amount = $totals['shipping_amount'];
	    		$order->setSubtotal($subtotal);
	    		$order->setShippingAmount($shipping_amount);
	    		$grand_total = $subtotal + $shipping_amount;
	    		$order->setGrandTotal($grand_total);
	    		 
	    		$order->save();
    		}
    		catch (Mage_Core_Exception $e) {
	    		$response = array(
	    			'error'     => true,
	    			'message'   => $e->getMessage(),
	    					);
    		}
    		catch (Exception $e) {
    			$response = array(
    					'error'     => true,
    					'message'   => $this->__('Cannot update order totals.')
    			);
    		}

    }


}
