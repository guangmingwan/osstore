<?php
	/*
	* Magento Delivery Date & Customer Comment Extension
	*
	* @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
	* @version:	2.0
	*
	*/

$installer = $this;
$installer->startSetup();

$installer->run(
"CREATE TABLE IF NOT EXISTS `{$installer->getTable('sales_ddc_order')}` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `customer_comment` text,
  PRIMARY KEY (`entity_id`,`order_id`),
  KEY `FK_sales_ddc_order` (`order_id`),
  CONSTRAINT `FK_sales_ddc_order` FOREIGN KEY (`order_id`) REFERENCES `{$installer->getTable('sales_flat_order')}` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$installer->run(
"CREATE TABLE IF NOT EXISTS `{$installer->getTable('sales_ddc_quote')}` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(10) unsigned NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `customer_comment` text,
  PRIMARY KEY (`entity_id`,`quote_id`),
  KEY `FK_sales_ddc_quote` (`quote_id`),
  CONSTRAINT `FK_sales_ddc_quote` FOREIGN KEY (`quote_id`) REFERENCES `{$installer->getTable('sales_flat_quote')}` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
$installer->endSetup();