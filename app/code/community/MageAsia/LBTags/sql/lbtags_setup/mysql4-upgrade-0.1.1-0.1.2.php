<?php
 

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
ALTER TABLE  {$this->getTable('lbtags/tags_relation')} CHANGE  `product_id`  `product_id` INT( 11 ) NOT NULL DEFAULT  '0';

 
");


$installer->endSetup();
