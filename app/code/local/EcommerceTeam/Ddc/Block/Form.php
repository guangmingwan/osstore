<?php
    /*
    * Magento Delivery Date & Customer Comment Extension
    *
    * @copyright:    EcommerceTeam (http://www.ecommerce-team.com)
    * @version:    2.0
    *
    */
    
    class EcommerceTeam_Ddc_Block_Form extends Mage_Checkout_Block_Onepage_Abstract{
        
        protected $start_date;
        protected $delivery_date;
        protected $customer_comment;
        
        public function getStartDate(){
            if(is_null($this->start_date)){
                
                $helper = Mage::helper('ecommerceteam_ddc');
                
                $date = Mage::app()->getLocale()->date();
                $day = intval($date->toString('d', 'php'));
                $date->setDay($day);
                if($days = trim($helper->getConfigData('disable_week_days'))){
                    $days = explode(',', $days);
                    if(!empty($days)){
                        while(in_array(($date->toString('w', 'php')+1), $days)){
                            
                            $date->setDay(intval($date->toString('d', 'php'))+1);
                            
                        }
                    }
                    
                }
                $this->start_date = $date->toString('dd MMMM YYYY');
            }
            return $this->start_date;
        }
        
        public function getDeliveryDate(){
            
            if(is_null($this->delivery_date)){
                
                $helper = Mage::helper('ecommerceteam_ddc');
                
                //$this->delivery_date = $this->getCheckout()->getQuote()->getDeliveryDate();
                
                $quote = $this->getCheckout()->getQuote();
                
                $date = Mage::app()->getLocale()->date();
                
                $date->setTimestamp($date->getTimestamp()+$date->getGmtOffset());
                
                if($sql_date = trim($quote->getDeliveryDate())){
                    
                    $date->setTimestamp(strtotime($sql_date)+$date->getGmtOffset());
                    
                }else{
                
                    $day = intval($date->toString('d', 'php'))+intval($helper->getConfigData('min_day'));
                    
                    $date->setDay($day);
                    
                }
                
                if($days = trim($helper->getConfigData('disable_week_days'))){
                    $days = explode(',', $days);
                    if(!empty($days)){
                        while(in_array(($date->toString('w', 'php')+1), $days)){
                            
                            $date->setDay(intval($date->toString('d', 'php'))+1);
                            
                        }
                    }
                    
                }
                
                $this->delivery_date = $date->toString('dd MMMM YYYY');
                
            }
            return $this->delivery_date;
        }
        
        public function getCustomerComment(){
            
            if(is_null($this->customer_comment)){
                $this->customer_comment = $this->getCheckout()->getQuote()->getCustomerComment();
            }
            return $this->customer_comment;
        }
        public function getConfigData($node){
            
            return Mage::helper('ecommerceteam_ddc')->getConfigData($node);
            
        }
        public function getFirstDay(){
            
            return intval(Mage::getStoreConfig('general/locale/firstday'));
            
        }
        public function getCommentLabel(){
            return $this->getConfigData('comment_label');
        }
        public function getDateLabel(){
            return $this->getConfigData('deliverydate_label');
        }
        
    }