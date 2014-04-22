<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomOptions
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Advanced Product Options extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomOptions
 * @author     MageWorx Dev Team
 */

/* @var $installer MageWorx_CustomOptions_Model_Mysql4_Setup */
$installer = $this;
$installer->startSetup();
// fix and clean up the debris of tables whith options
$installer->run("
    DELETE t1 FROM `{$installer->getTable('catalog_product_option')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_entity')}` WHERE `entity_id` = t1.`product_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('catalog_product_option_title')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option')}` WHERE `option_id` = t1.`option_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('catalog_product_option_price')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option')}` WHERE `option_id` = t1.`option_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('catalog_product_option_type_value')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option')}` WHERE `option_id` = t1.`option_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('catalog_product_option_type_title')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option_type_value')}` WHERE `option_type_id` = t1.`option_type_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('catalog_product_option_type_price')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option_type_value')}` WHERE `option_type_id` = t1.`option_type_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('custom_options_relation')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option')}` WHERE `option_id` = t1.`option_id`) = 0;
    DELETE t1 FROM `{$installer->getTable('custom_options_option_description')}` AS t1 WHERE (SELECT COUNT(*) FROM `{$installer->getTable('catalog_product_option')}` WHERE `option_id` = t1.`option_id`) = 0;
");
$installer->endSetup();