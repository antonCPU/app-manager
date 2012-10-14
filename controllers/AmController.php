<?php

Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.search.*');

class AmController extends AppManagerController
{
    public $defaultAction = 'list';
    protected $title = 'Am test';
    
    public function actionList()
    {
        $app = new AmEntityCore;
        
        //$search = new AmSearchModule('application.modules');

        $this->render('list', array(
            'list' => $app->modules,
        ));
    }
    
    public function actionView($id)
    {
        $entity = $this->getEntity();

        $this->render('view', array(
            'entity' => $entity,
        ));
    }
    
    public function actionUpdate($id)
    {
        $entity = $this->getEntity();
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
    
    /**
     * @param string $id 
     */
    public function actionActivate($id)
    {
        $this->getEntity()->activate();
        $this->redirect(array('update', 'id' => $id));
    }
    
    /**
     * @param string $id 
     */
    public function actionDeactivate($id)
    {
        $this->getEntity()->deactivate();
        $this->redirect(array('list'));
    }
    
    protected function needRestore()
    {
        return (bool)$this->getPost('restore');
    }
    
    public function getEntity()
    {
        $search = new AmSearchCoreModule('system');
        return $search->findById($this->getParam('id'));
    }
}