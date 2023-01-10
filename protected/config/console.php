<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'LBS Daily Management - UAT',
	'timeZone'=>'Asia/Hong_Kong',
	'sourceLanguage'=> 'en',
	'language'=>'zh_cn',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.YiiMailer.YiiMailer',
	),

	// application components
	'components'=>array(
		'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=hrdev',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'swisher168',
            'charset' => 'utf8',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'systemId'=>'hr',
		'systemEmail'=>'it@lbsgroup.com.hk',
		'adminEmail'=>'it@lbsgroup.com.hk',
		'webroot'=>'http://http://118.89.46.224/hr',
		'envSuffix'=>'dev',
        'retire'=>true,
        'yearLeave'=>'employee', //employee:年假根據員工信息的年假計算

        'unitedKey' => '5afa24ed2469449da16d8e74bf039a78',
        'unitedRootURL'=>'https://app.lbsapps.cn/web',
	),
);
