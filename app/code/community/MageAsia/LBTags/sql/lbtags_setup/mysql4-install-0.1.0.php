<?php


$installer = $this;

$installer->startSetup();
$sql="
DROP TABLE IF EXISTS {$this->getTable('lbtags/tags')};
CREATE TABLE {$this->getTable('lbtags/tags')} (
`tags_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`status` smallint( 11 ) NOT NULL default '0',
`name` varchar( 255 ) NOT NULL default '',
`identifier` varchar( 255 ) NOT NULL default '',
`created_time` datetime default NULL ,
`meta_title` text NOT NULL ,
`meta_keywords` text NOT NULL ,
`meta_description` text NOT NULL ,
`comments`  text NOT NULL ,
PRIMARY KEY ( `tags_id` ) ,
UNIQUE KEY `identifier` ( `identifier` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS {$this->getTable('lbtags/tags_properties')};
CREATE TABLE {$this->getTable('lbtags/tags_properties')} (
`tags_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`store_id` smallint( 11 ) NOT NULL default '0',
 
PRIMARY KEY ( `tags_id` ) 
 
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS {$this->getTable('lbtags/tags_relation')};
CREATE TABLE {$this->getTable('lbtags/tags_relation')} (
`tag_relation_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`tags_id` smallint( 11 ) NOT NULL default '0',
`product_id` varchar( 255 ) NOT NULL default '',
`active` smallint( 1 ) NOT NULL default '1',
`created_at` datetime default NULL ,
 
PRIMARY KEY ( `tag_relation_id` ) 
 
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

";

try{
	
	
$installer->run($sql);


}catch(Exception $e){}
 
$installer->endSetup(); 
