<?php
/**
 * Handles actions related to entities.
 */
class AmEntityController extends AmController
{
    public $defaultAction = 'list';
    public $layout = '/layouts/column1';
    protected $defaultId;
    
    /**
     * @var AmEntity 
     */
    protected $model;
    
    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = array_reverse(parent::getBreadcrumbs());
        $entity = $this->getEntity();
        while ($entity = $entity->getParent()) {
            $breadcrumbs[$entity->getTitle()] = array('list', 'id' => $entity->getId());
        }

        return array_reverse($breadcrumbs);
    }
    
    public function actionList()
    {
        $this->render('list', array(
            'entity' => $this->getEntity(),
        ));
    }
   
    /**
     * @param string $id
     */
    public function actionView($id)
    { 
        $this->render('view', array(
           'entity' => $this->getEntity(), 
        ));
    }
    
    /**
     * @param string $id
     */
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
        } elseif (Yii::app()->request->isPostRequest) {
            $entity->attributes = $this->getPost(get_class($entity));
            $entity->options    = $this->getPost('AmOptions');
            
            if ($entity->save()) {
                $this->setEntityFlash('success', '{name} has been updated.');
                $this->redirect(array('list', 'id' => $entity->getParent()->getId()));
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
     * Gets requested by id entity.
     * @return AmEntity
     */
    public function getEntity()
    { 
        $model = $this->getModel();
        if ($id = $this->getQuery('id')) {
            if ($model->getId() == $id) {
                $id = $this->defaultId;
            }
        } else {
            $id = $this->defaultId;
        }
        return $model->getChild($id); 
    }
    
    /**
     * Checks if current section equals $section.
     * @param string $section
     * @return bool
     */
    public function isSection($section)
    {
        $parts = explode('.', $this->getEntity()->getId());
        return (false !== array_search($section, $parts));
    }
    
    /**
     * @return array compatible with CMenu.
     */
    public function getMenu()
    {
        $id = $this->getModel()->getId();
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
            array(
                'label'  => AppManagerModule::t('Extensions'), 
                'url'    => array('list', 'id' => $id . '.extensions'), 
                'active' => $this->isSection('extensions'),
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
                        array('{name}' => $this->getEntity()->title));
    }
}

