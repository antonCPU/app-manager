<?php

Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.search.*');

class AmController extends AppManagerController
{
    protected $title = 'Am test';
    
    public function actionList()
    {
        $search = new AmSearchModule('application.modules');
        
        $this->render('list', array(
            'search' => $search,
        ));
    }
    
    public function actionView($id)
    {
        $search = new AmSearchModule('application');
        
        $this->render('view', array(
            'entity' => $search->findById($id)
        ));
    }
    
    public function actionUpdate($id)
    {
        $search = new AmSearchModule('application');
        
        $this->render('update', array(
            'entity' => $search->findById($id)
        ));
    }
}