<?php
if(strpos($_SERVER['HTTP_HOST'], 'local') !== false){
    $yii=dirname(__FILE__).'/../../../Yii/yii.php';
    $config=dirname(__FILE__).'/protected/config/main_onad.php';
} else {
    $yii=dirname(__FILE__).'/../../Yii/yii.php';
    $config=dirname(__FILE__).'/protected/config/logol_snow.php';
}

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
require_once($config);

Yii::createWebApplication($config)->run();
