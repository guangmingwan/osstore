<?php
	
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/
	
	class EcommerceTeam_Ddc_Model_Observer{
		
		public function coreBlockAbstractPrepareLayoutBefore($event){
			
			switch($event->getBlock()->getType()):
			case ('adminhtml/sales_order_grid'):
				
				
				
		        if(Mage::getVersion() < '1.4.0'){
			        
			        $event->getBlock()->addColumn('delivery_date', array(
			            'header' => Mage::helper('sales')->__('Delivery Date'),
			            'type' => 'date',
			            'index' => 'delivery_date',
			            'width' => '160px',
			        ));
		        
		        }else{
		        	$event->getBlock()->addColumnAfter('delivery_date', array(
		            'header' => Mage::helper('sales')->__('Delivery Date'),
		            'type' => 'date',
		            'index' => 'delivery_date',
		            'width' => '160px',
			        ), 'created_at');
		        
		        }
		        
			break;
			endswitch;
		}
		
		public function loadOrderData($event){
			
			$order = $event->getOrder();
			
			$data = Mage::getModel('ecommerceteam_ddc/order')->load($order->getEntityId(), 'order_id')->getData();
			
			if(isset($data['order_id'])){
				
				unset($data['entity_id'], $data['order_id']);
				
				if(strtotime($data['delivery_date'])){
					
					$formated_date = Mage::getSingleton('core/locale')->date($data['delivery_date'], Zend_Date::ISO_8601, null, false)->toString(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_FULL));
					
					$data['delivery_date_formated'] = $formated_date;
					
				}else{
					unset($data['delivery_date']);
				}
				
				$order->addData($data);
				
			}
			
		}
		
		public function loadQuoteData($event){
			
			$quote = $event->getQuote();
			
			$data = Mage::getModel('ecommerceteam_ddc/quote')->load($quote->getEntityId(), 'quote_id')->getData();
			
			if(isset($data['quote_id'])){
				
				unset($data['entity_id'], $data['quote_id']);
				
				$quote->addData($data);
				
			}
			
		}
		
		public function saveOrderData($event){
			
			
			$order = $event->getOrder();
			$quote = Mage::getSingleton('checkout/session')->getQuote();
			
			try{
				$model = Mage::getModel('ecommerceteam_ddc/order')
				->setOrderId($order->getEntityId())
				->setDeliveryDate($quote->getDeliveryDate())
				->setCustomerComment($quote->getCustomerComment())
				->save();
			}catch(Exception $e){
					//continue
			}
		}
		
		public function saveQuoteData($event){
			
			$helper = Mage::helper('ecommerceteam_ddc');
			
			$quote = $event->getQuote();
			
			$request	= Mage::app()->getRequest();
			
			$model = Mage::getModel('ecommerceteam_ddc/quote')->load($quote->getEntityId(), 'quote_id');
			
			if(!$model->getEntityId()){
				$model->setQuoteId($quote->getEntityId());
			}
			
			$data = array();
			
			$date = Mage::app()->getLocale()->date();
			
			if($request->getParam('delivery_date')){
				
				$deliverydate = Date('Ymd', strtotime($request->getParam('delivery_date')));
				
			}elseif(!$model->getEntityId()){
				
				$date->setDay($date->toString('d')+intval($helper->getConfigData('min_day')));
				
				$deliverydate = Date('Ymd', $date->getTimeStamp()+$date->getGmtOffset());
				
				
			}
			
			if(isset($deliverydate)){
				$data['delivery_date'] = $deliverydate;
			}
			
			if($customer_comment = $request->getParam('customer_comment')){
				$data['customer_comment'] = $customer_comment;
			}
			
			if(!empty($data)){
				
				$quote->addData($data);
				
				try{
				
					$model->addData($data)->save();
				
				}catch(Exception $e){
					//continue
				}
			
			}
			
		}
		
	}