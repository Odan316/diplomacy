<?php

return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	// application components
	'components'=>array(
		'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=diplomacy',
            'emulatePrepare' => true,
            'username' => 'diplo',
            'password' => 'unmateoph4ia',
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
    'params'=>array(
        // this is used in contact page
        'rootPath'=>$_SERVER['DOCUMENT_ROOT'].'/diplo2',
        'globalsalt' => 'DiplomacyGame'
    ),
));
?>