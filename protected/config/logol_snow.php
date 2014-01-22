<?php

return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	// application components
	'components'=>array(
		'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=cp460843_diplomacy',
            'emulatePrepare' => true,
            'username' => 'cp460843_diplo',
            'password' => 'ieQu4le_du',
            'charset' => 'utf8',
            'enableProfiling' => YII_DEBUG,
            'enableParamLogging' => YII_DEBUG,
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
));
?>