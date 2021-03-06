<?php
return CMap::mergeArray(
    require( dirname( __FILE__ ) . '/main.php' ),
    [
        'basePath'   => dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..',
        // application components
        'components' => [
            'db'  => [
                'connectionString'   => 'mysql:host=localhost;dbname=diplomacy',
                'emulatePrepare'     => true,
                'username'           => 'diplo',
                'password'           => 'unmateoph4ia',
                'charset'            => 'utf8',
                'enableProfiling'    => YII_DEBUG,
                'enableParamLogging' => YII_DEBUG,
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
        'params'     => [
            // this is used in contact page
            'rootPath'   => $_SERVER['DOCUMENT_ROOT'] . '/diplomacy',
            'globalsalt' => 'DiplomacyGame'
        ],
    ]
);