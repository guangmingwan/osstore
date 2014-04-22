<?php
 

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('lbtags/tags')}
	ADD `tagcount` INT( 11 ) NOT NULL AFTER `status` ;
");


$installer->endSetup();