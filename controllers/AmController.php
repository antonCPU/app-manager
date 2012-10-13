<?php

Yii::import('appManager.components.entity.*');

class AmController extends AppManagerController
{
    public $defaultAction = 'test';
    protected $title = 'Am test';
    
    public function actionTest()
    {
        $entity = new AmEntity('ext.EWordValidator');
        foreach ($entity->options as $option) {
            myd($option);
        }
    }
}