<?php

Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.search.*');

class AmController extends AppManagerController
{
    protected $title = 'Am test';
    
    public function actionList()
    {
        $search = new AmSearchComponent('application');
        
        $this->render('list', array(
            'search' => $search,
        ));
    }
    
    public function actionView($id)
    {
        $search = new AmSearchComponent('application');
        
        $this->render('view', array(
            'entity' => $search->findById($id)
        ));
    }
}