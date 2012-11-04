<?php
/**
 * Handles actions related to entities.
 */
class AmEntityController extends AmController
{
    public $defaultAction = 'list';
    public $layout = '/layouts/column1';
    
    /**
     * @var AmEntity 
     */
    protected $model;
    protected $entity;
    protected $baseEntity;
    
    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $entity = $this->getEntity();
        if ('update' === $this->action->id) {
            $breadcrumbs[] = AppManagerModule::t('Update');
            $breadcrumbs[$entity->getTitle()] = $this->getEntityUrl($entity);
        } else {
            $breadcrumbs[] = $entity->getTitle();
        }
        while ($entity = $entity->getParent()) {
            $breadcrumbs[$entity->getTitle()] = $this->getEntityUrl($entity);
        }
        return array_reverse($breadcrumbs);
    }
    
    /**
     * Shows list of entities.
     */
    public function actionList()
    {
        $entity = $this->getEntity();
        if (!$entity->canList()) {
            $this->setEntityFlash('error', 'Unable to list {name}.');
            $this->redirectParent();
        }
        $this->render('list', array(
            'entity' => $entity,
        ));
    }
   
    /**
     * Shows an entity details.
     * @param string $id
     */
    public function actionView($id)
    { 
        $entity = $this->getEntity();
        if (!$entity->canView()) {
            $this->setEntityFlash('error', 'Unable to view {name}.');
            $this->redirectParent();
        }
        $this->render('view', array(
           'entity' => $this->getEntity(), 
        ));
    }
    
    /**
     * Handles an entity update.
     * @param string $id
     */
    public function actionUpdate($id)
    { 
        $entity = $this->getEntity();
        if (!$entity->canUpdate() && $entity->getParent()) {
            $this->setEntityFlash('error', 'Unable to update {name}.');
            $this->redirectParent();
        }
        
        if ($this->getPost('restore')) {
            if ($entity->restore()) {
                $this->setEntityFlash('success', '{name} has been restored.');
            } else {
                $this->setEntityFlash('error', 'Unable to restore {name}.');
            }
        } elseif (Yii::app()->request->isPostRequest) {
            $entity->attributes = $this->getPost(get_class($entity));
            $entity->options    = $this->getPost('AmOptions');
            
            if ($entity->save()) {
                $this->setEntityFlash('success', '{name} has been updated.');
                $this->redirectParent();
            } else {
                $this->setEntityFlash('error', 'Unable to update. Incorrect input.');
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
            $this->redirectParent();
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
        $this->redirectParent();
    }
    
    /**
     * Gets a url for the parent of current entity.
     * @return array
     */
    protected function redirectParent()
    {
        $this->redirect($this->getEntityUrl($this->getEntity()->getParent()));
    }
    
    /**
     * Gets a url for an entity.
     * @param AmEntity $entity
     * @return array
     */
    public function getEntityUrl($entity)
    {
        $url = array($this->defaultAction);
        if (!$entity) {
            return $url;
        }
        if ($entity->canList()) {
            $url = array('list', 'id' => $entity->getId());
        } elseif ($entity->canView()) {
            $url = array('view', 'id' => $entity->getId());
        }
        return $url;
    }
    
    /**
     * Gets base entity model.
     * @return AmEntity
     */
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = $this->createModel();
        }
        return $this->model;
    }
    
    /**
     * Factory method.
     * @return AmEntity
     */
    protected function createModel()
    {
        return new AmEntity;
    }
    
    /**
     * Gets an entity according to which the menu is built.
     * @return AmEntity
     */
    protected function getBase()
    {
        if (null === $this->baseEntity) {
            $entity = $this->getEntity();
            $base = $entity->getParent();
            if (!$base) {
                $base = $entity;
            } elseif ('list' !== $this->action->id) {
                $base = $base->getParent();
            } 
            $this->baseEntity = $base;
        }
        return $this->baseEntity;
    }
    
    /**
     * Gets requested by id entity.
     * @return AmEntity
     */
    public function getEntity()
    { 
        if (null === $this->entity) {
            $entity = $this->getModel();
            if (($id = $this->getQuery('id')) && $id !== $entity->getId()) {
                $entity = $entity->getChild($id);
            }
            $this->entity = $entity;
        } 
        return $this->entity; 
    }
    
    /**
     * Checks to which collection belongs entity. (components, modules...)
     * @param string $name
     * @return bool
     */
    public function isSection($name)
    {
        $parts = explode('.', $this->getEntity()->getId()); 
        return ($name === array_pop($parts));
    }
    
    /**
     * @return array compatible with CMenu.
     */
    public function getMenu()
    {
        $id = $this->getBase()->getId();
        return array(
            array(
                'label'  => AppManagerModule::t('Components'), 
                'url'    => array('list', 'id' => $id . '.components'), 
                'active' => $this->isSection('components'),
            ),
            array(
                'label'  => AppManagerModule::t('Modules'), 
                'url'    => array('list', 'id' => $id . '.modules'), 
                'active' => $this->isSection('modules'),
            ),
        );
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
                        array('{name}' => $this->getEntity()->getTitle()));
    }
}

