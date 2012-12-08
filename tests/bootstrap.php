<?php

defined('AM_DATA_DIR') or define('AM_DATA_DIR', dirname(__FILE__).'/data');

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../../framework/yiit.php';
$config=AM_DATA_DIR.'/config.php';


require_once($yiit);

Yii::createWebApplication($config);
