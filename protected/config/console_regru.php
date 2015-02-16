<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return [
    'basePath'   => dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..',
    'name'       => 'My Console Application',
    // preloading 'log' component
    'preload'    => [ 'log' ],
    // application components
    'components' => [
        'db'  => [
            'connectionString' => 'mysql:host=localhost;dbname=u0058909_default',
            'emulatePrepare'   => true,
            'username'         => 'u0058909_default',
            'password'         => 'qqZvOX2!',
            'charset'          => 'utf8',
        ],
        'log' => [
            'class'  => 'CLogRouter',
            'routes' => [
                [
                    'class'  => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ],
            ],
        ],
    ],
];