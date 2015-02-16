<?php
if (strpos( $_SERVER['HTTP_HOST'], 'local' ) !== false) {
    $yii    = dirname( __FILE__ ) . '/yii/framework/yii.php';
    $config = dirname( __FILE__ ) . '/protected/config/main_onad.php';
    defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );
} else {
    $yii    = dirname( __FILE__ ) . '/../yii/framework/yii.php';
    $config = dirname( __FILE__ ) . '/protected/config/regru.php';
    define( 'YII_DEBUG', false );
}
// specify how many levels of call stack should be shown in each log message
defined( 'YII_TRACE_LEVEL' ) or define( 'YII_TRACE_LEVEL', 3 );

require_once( $yii );
require_once( $config );

Yii::createWebApplication( $config )->run();
