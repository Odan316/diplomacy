<?php

if (strpos( $_SERVER['HTTP_HOST'], 'local' ) !== false) {
    $yiic   = dirname( __FILE__ ) . '/../yii/framework/yiic.php';
    $config = dirname( __FILE__ ) . '/config/console.php';
} else {
    $yiic   = dirname( __FILE__ ) . '/../../yii/framework/yiic.php';
    $config = dirname( __FILE__ ) . '/config/console_regru.php';
}

require_once( $yiic );