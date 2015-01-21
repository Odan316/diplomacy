<?php
Yii::setPathOfAlias('diplomacy', dirname(__FILE__).DIRECTORY_SEPARATOR.'..');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Diplomacy Game Platform',
    'sourceLanguage' => 'ru',

	// preloading 'log' component
	'preload'=>array(
        'bootstrap',
        'debug',
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	'modules'=>array(
        //'antiquity',
        'project13',
		'vestria' => [
			"class" => '\diplomacy\modules\vestria\VestriaModule'
		]
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'session' => array(
            'class'        => 'CDbHttpSession',
            'connectionID' => 'db',       // БД для хранения сессий
            'autoStart'    => true,       // Автоматический старт сессии
        ),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
			'rules'=>array(
				//'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
        'debug' => array(
            'class' => 'ext.yii2-debug.Yii2Debug',
            'enabled' => true
        ),
        'db' => array(
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'bootstrap' => array(
            'class' => 'ext.yiibooster.src.components.Bootstrap',
            //'popoverSelector' => false,
            //'tooltipSelector' => false
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'si.andreev316@gmail.com',
        'globalsalt' => 'DiplomacyGame'
	),
);
?>