<?php

class AppController extends AmController
{
    public $defaultAction = 'components';
    
    public function actionComponents()
    {
        $this->render('list', array(
           'list' => $this->getModel()->getComponents() 
        ));
    }
    
    public function actionModules()
    {
        $this->render('list', array(
           'list' => $this->getModel()->getModules() 
        ));
    }
    
    public function actionExtensions()
    {
        $this->render('list', array(
           'list' => $this->getModel()->getExtensions() 
        ));
    }
    
    public function actionView($id)
    { 
        $this->render('view', array(
           'entity' => $this->getEntity(), 
        ));
    }
    
    public function actionUpdate($id)
    { 
        $entity = $this->getEntity();
        
        if ($this->getPost('restore')) {
            if ($entity->restore()) {
               // $this->setEntityFlash('success', '{name} has been restored.');
            } else {
               // $this->setEntityFlash('error', 'Unable to restore {name}.');
            }
        } elseif ($data = $this->getPost(get_class($entity))) {
            $entity->attributes = $data;
            $entity->options    = $this->getPost('AmOptions');
            
            if ($entity->save()) {
               // $this->setEntityFlash('success', '{name} has been updated.');
                //$this->redirect(array('list'));
            } else {
                //$this->setEntityFlash('error', 'Unable to update {name}.');
            }
        }
        
        $this->render('update', array(
           'entity' => $entity, 
        ));
    }
    
    public function getModel()
    {
        return new AmEntityApp;
    }
    
    public function getEntity()
    {
        return $this->getModel()->findById($this->getParam('id')); 
    }
}
