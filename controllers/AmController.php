<?php

Yii::import('appManager.components.entity.*');

class AmController extends AppManagerController
{
    public $defaultAction = 'test';
    protected $title = 'Am test';
    
    public function actionTest()
    {
        $entity = new AmEntity('ext.EWordValidator');
        myd($entity->author);
    }
}