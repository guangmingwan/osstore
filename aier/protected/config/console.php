<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Git切换工具 console版本',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*', 
		'application.components.*',
	),
		
	'modules'=>array(
			'gii'=>array(
					'class'=>'system.gii.GiiModule',
					'password'=>'790128083',
					// 'ipFilters'=>array(...a list of IPs...),
					// 'newFileMode'=>0666,
					// 'newDirMode'=>0777,
			),
	),
	'components'=>array(
			'user'=>array(
					// enable cookie-based authentication
					'allowAutoLogin'=>true,
			),
			'db'=>array(
					'connectionString' => 'sqlite:protected/data/blog.db',
					'tablePrefix' => 'tbl_',
			),
	)
);