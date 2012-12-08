<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../../framework/yiit.php';
$config=dirname(__FILE__).'/data/config.php';

define('AM_DATA_DIR', dirname(__FILE__).'/data');

require_once($yiit);

Yii::createWebApplication($config);
