<?php

class AmEntityController extends AmController
{
    public $defaultAction = 'components';
    
    protected $model;
    
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
        if (!$entity->canUpdate()) {
            $this->setEntityFlash('error', 'Unable to update {name}.');
            $this->redirect(array($this->getSection()));
        }
        
        if ($this->getPost('restore')) {
            if ($entity->restore()) {
                $this->setEntityFlash('success', '{name} has been restored.');
            } else {
                $this->setEntityFlash('error', 'Unable to restore {name}.');
            }
        } elseif ($data = $this->getPost(get_class($entity))) {
            $entity->attributes = $data;
            $entity->options    = $this->getPost('AmOptions');
            
            if ($entity->save()) {
                $this->setEntityFlash('success', '{name} has been updated.');
                $this->redirect(array($this->getSection()));
            } else {
                $this->setEntityFlash('error', 'Unable to update {name}.');
            }
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
        if ($this->getEntity()->activate()) {
            $this->setEntityFlash('success', '{name} has been activated.');
            $this->redirect(array('update', 'id' => $id));
        } else {
            $this->setEntityFlash('error', 'Unable to activate {name}.');
            $this->redirect(array($this->getSection()));
        }
    }
    
    /**
     * @param string $id 
     */
    public function actionDeactivate($id)
    {
        if ($this->getEntity()->deactivate()) {
            $this->setEntityFlash('success', '{name} has been deactivated.');
        } else {
            $this->setEntityFlash('error', 'Unable to deactivate {name}.');
        }
        $this->redirect(array($this->getSection()));
    }
    
    /**
     * @param string $id 
     */
    public function actionDelete($id)
    {
        if ($this->getEntity()->delete()) {
            $this->setEntityFlash('success', '{name} has been deleted.');
        } else { 
            $this->setEntityFlash('error', 'Unable to delete {name}.');
        }
        $this->redirect(array($this->getSection()));
    }
    
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = new AmEntityModule;
        }
        return $this->model;
    }
    
    public function getEntity()
    {
        return $this->getModel()->findById($this->getParam('id')); 
    }
    
    public function getSection()
    {
        return $this->getModel()->getSection();
    }
    
    public function getSectionTitle()
    {
        return ucfirst($this->getSection());
    }
    
    /**
     * Sets a flash message related to the entity.
     * @param string $flashType
     * @param string $message 
     * @see AppManagerController::setFlash() for flash types.
     */
    protected function setEntityFlash($flashType, $message)
    {
        $this->setFlash($flashType, $message, 
                        array('{name}' => $this->getEntity()->title));
    }
}

