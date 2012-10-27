<?php
/**
 * Handles actions related to entities.
 */
class AmEntityController extends AmController
{
    public $defaultAction = 'components';
    public $layout = '/layouts/column1';
    /**
     * @var AmEntity 
     */
    protected $model;
    
    /**
     * @return string
     */
    public function getPageTitle()
    {
        return parent::getPageTitle() . ' - ' . $this->getSectionTitle();
    }
    
    public function actionComponents()
    {
        $this->render('list', array(
           'list' => $this->getChildrenProvider('components'), 
        ));
    }
    
    public function actionModules()
    {
        $this->render('list', array(
           'list' => $this->getChildrenProvider('modules'),
        ));
    }
    
    public function actionExtensions()
    {
        $this->render('list', array(
           'list' => $this->getChildrenProvider('extensions'), 
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
                $this->redirect(array($this->getSection()));
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
        return $this->getModel()->getChild($this->getParam('id')); 
    }
    
    /**
     * @return string
     */
    public function getSection()
    {
        $section = $this->action->id;
        if (!in_array($section, array('components', 'modules', 'extensions'))) {
            $section = $this->getEntity()->getParent()->getName();
        }
        return $section;
    }
    
    /**
     * @return string
     */
    public function getSectionTitle()
    {
        return ucfirst($this->getSection());
    }
    
    /**
     * Checks if current section equals $section.
     * @param string $section
     * @return bool
     */
    public function isSection($section)
    {
        return ($this->getSection() === $section);
    }
    
    /**
     * @return array compatible with CMenu.
     */
    public function getMenu()
    {
        return array(
            array(
                'label'  => AppManagerModule::t('Components'), 
                'url'    => array('components'), 
                'active' => $this->isSection('components'),
            ),
            array(
                'label'  => AppManagerModule::t('Modules'), 
                'url'    => array('modules'), 
                'active' => $this->isSection('modules'),
            ),
            array(
                'label'  => AppManagerModule::t('Extensions'), 
                'url'    => array('extensions'), 
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
    
    /**
     * @param string $section
     * @return CArrayDataProvider
     */
    protected function getChildrenProvider($section)
    {
        return $this->getModel()->getChild($section)->getChildrenProvider();
    }
}

