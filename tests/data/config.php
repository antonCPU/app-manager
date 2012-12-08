<?php

return array(
    'basePath' => dirname(__FILE__).'/../../../..',
    'name' => 'Test App Manager',
    'modules' => array(
        'appManager' => array(
            'class' => 'application.modules.appManager.AppManagerModule',
        ),
    ),
);