<?php

Yii::import('appManager.components.entity.*');

class AmController extends AppManagerController
{
    public $defaultAction = 'test';
    protected $title = 'Am test';
    
    public function actionTest()
    {
        $entity = new AmParser(Yii::getPathOfAlias('ext.EWordValidator'));
        myd($entity->properties[0]->value);
    }
}