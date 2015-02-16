<?php
Yii::setPathOfAlias( 'diplomacy', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' );

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return [
    'basePath'       => dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..',
    'name'           => 'Diplomacy Game Platform',
    'sourceLanguage' => 'ru',
    // preloading 'log' component
    'preload'        => [
        'bootstrap',
        'debug',
    ],
    // autoloading model and component classes
    'import'         => [
        'application.models.*',
        'application.components.*',
    ],
    'modules'        => [
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
    ],
    // application components
    'components'     => [
        'user'         => [
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ],
        'session'      => [
            'class'        => 'CDbHttpSession',
            'connectionID' => 'db',       // БД для хранения сессий
            'autoStart'    => true,       // Автоматический старт сессии
        ],
        // uncomment the following to enable URLs in path-format
        'urlManager'   => [
            'urlFormat'      => 'path',
            'showScriptName' => false,
            'rules'          => [
                //'<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ]
        ],
        'errorHandler' => [
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ],
        'debug'        => [
            'class'   => 'ext.yii2-debug.Yii2Debug',
            'enabled' => true
        ],
        'db'           => [
            'enableProfiling'    => true,
            'enableParamLogging' => true,
        ],
        'bootstrap'    => [
            'class' => 'ext.yiibooster.src.components.Bootstrap',
            //'popoverSelector' => false,
            //'tooltipSelector' => false
        ],
    ],
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'         => [
        // this is used in contact page
        'adminEmail' => 'si.andreev316@gmail.com',
        'globalsalt' => 'DiplomacyGame'
    ],
];