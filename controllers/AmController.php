<?php

Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.search.*');

class AmController extends AppManagerController
{
    public $defaultAction = 'list';
    protected $title = 'Am test';
    
    public function actionList()
    {
        $search = new AmSearchModule('application.modules');
        
        $this->render('list', array(
            'list' => $search->perform(),
        ));
    }
    
    public function actionView($id)
    {
        $entity = new AmEntityModule($id);

        $this->render('view', array(
            'entity' => $entity,
        ));
    }
    
    public function actionUpdate($id)
    {
        $entity = new AmEntityModule($id);
         if ($this->needRestore()) {
            $entity->restore();
        } elseif ($data = $this->getPost('AmEntityModule')) {
            $entity->attributes = $data;
            $entity->options    = $this->getPost('AmOptions');
            
            $entity->save();
        }
        
        $this->render('update', array(
            'entity' => $entity,
        ));
    }
    
        protected function needRestore()
    {
        return (bool)$this->getPost('restore');
    }
}