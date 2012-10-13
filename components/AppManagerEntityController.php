<?php
/**
 * Common controller for all entities.
 */
class AppManagerEntityController extends AppManagerController
{
    public $defaultAction = 'list';
    
    /**
     * @var string 
     */
    protected $entityClass;
    /**
     * @var AppManagerEntity is used for all pages except the listing.
     */
    protected $entity;
    
    /**
     * Shows list of entities.
     */
    public function actionList()
    { 
        $this->render('list', array('entities' => $this->createEntity()->search()));
    }
    
    /**
     * Shows a specific entity.
     * @param string $id 
     */
    public function actionView($id)
    {
        $this->render('view', array('entity' => $this->getEntity()));
    }
    
    /**
     * Shows an edit page.
     * Performs saving and restoring.
     * @param string $id 
     */
    public function actionUpdate($id)
    {
        $entity = $this->getEntity(); 
        if (!$entity->canUpdate()) {
            $this->setEntityFlash('error', 'Unable to update {name}.');
            $this->redirect(array('index'));
        }

        if ($this->needRestore()) {
            if ($entity->restore()) {
                $this->setEntityFlash('success', '{name} has been restored.');
            } else {
                $this->setEntityFlash('error', 'Unable to restore {name}.');
            }
        } elseif ($data = $this->getPost($this->getEntityClass())) {
            $entity->attributes = $data;
            $entity->options    = $this->getPost($this->getOptionClass());
            
            if ($entity->save()) {
                $this->setEntityFlash('success', '{name} has been updated.');
                $this->redirect(array('index'));
            } else {
                $this->setEntityFlash('error', 'Unable to update {name}.');
            }
        }
        
        $this->render('update', array('entity' => $entity));
    }
        
    /**
     * Determines if the current update request should be used for restoring.
     * @return bool 
     */
    protected function needRestore()
    {
        return (bool)$this->getPost('restore');
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
            $this->redirect(array('index'));
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
        $this->redirect(array('index'));
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
        $this->redirect(array('index'));
    }
    
    /**
     * Gets an entity instance using request params.
     * @return AppManagerEntity 
     * @throws CHttpException if entity can't be created.
     */
    public function getEntity()
    {
        if (null === $this->entity) {
            if (!$id = $this->getParam('id')) {
                throw new CHttpException(404, 
                        AppManagerModule::t('Can\'t resolve an empty id.'));
            }
            $this->entity = $this->createEntity($this->getParam('id'));
        }
        return $this->entity;
    }

    /**
     * Creates an entity instance.
     * @param string $id
     * @return AppManagerEntity
     * @throws CHttpException in case when $id was set, 
     *                        but the entity can't be created. 
     */
    protected function createEntity($id = null)
    {
        $class = $this->getEntityClass();
        $entity = new $class;
        try {
            $entity = $entity->findById($id);
        } catch (CException $e) {
            throw new CHttpException(404, $e->getMessage());
        }
        return $entity;
    }
    
    /**
     * Determines class name for entity options.
     * Used for dealing with CActiveForm form elements naming convention.
     * @return string
     */
    protected function getOptionClass()
    {
        return get_class($this->getEntity()->getOptions());
    }

    /**
     * @return string 
     */
    protected function getEntityClass()
    {
        return $this->entityClass;
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
                        array('{name}' => $this->getEntity()->name));
    }
    
    /**
     * Determines class name for entity options.
     * Used for dealing with CActiveForm form elements naming convention.
     * @return string
     */
    protected function getOptionClass()
    {
        return get_class($this->getEntity()->getOptions());
    }
}