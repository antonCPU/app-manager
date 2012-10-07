<?php
/**
 * Common controller.
 */
class AppManagerController extends CController
{
    /**
	 * @var string
	 */
	public $layout = null;
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    
    /**
     * @var string section title. 
     */
    protected $title;
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
	public function actionIndex()
	{ 
		$this->render($this->getBaseViewPath() . 'index', 
                      array('entities' => $this->createEntity()->search()));
	}
    
    /**
     * Shows a specific entity.
     * @param string $id 
     */
    public function actionView($id)
    {
        $this->render($this->getBaseViewPath() . 'view', 
                      array('entity' => $this->getEntity()));
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
        
        $this->render($this->getBaseViewPath() . 'update', 
                      array('entity' => $entity));
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
     * Forms page title.
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->module->name . ' - ' . $this->getTitle();
    }
    
    /**
     * Gets localized section title.
     * @return string 
     */
    public function getTitle()
    { 
        return AppManagerModule::t($this->title);
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
     * Checks if controller id equals to needed. 
     * Mostly used for view.
     * @param string $id controller id.
     * @return bool 
     */
    public function isId($id)
    {
        return ($id === $this->getId());
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
     * Gets path to common view directory.
     * Should be empty if a subclass wants to use default by Yii templates directory. 
     * @return string 
     */
    protected function getBaseViewPath()
    {
        return '../default/';
    }
    
    /**
     * Gets data from POST request.
     * @param string $name
     * @param mixed  $default
     * @return mixed 
     */
    protected function getPost($name, $default = null)
    {
        return Yii::app()->getRequest()->getPost($name, $default);
    }
    
    /**
     * Gets data from GET request.
     * @param string $name
     * @param mixed  $default
     * @return mixed 
     */
    protected function getParam($name, $default = null)
    {
        return Yii::app()->getRequest()->getParam($name, $default);
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
     * Wrapper to Yii user->setFlash() functionality.
     * All messages will be localized for AppManager module.
     * @param string $type could be: 'success', 'notice', 'error'.
     * @param string $message with placeholders.
     * @param array  $params placeholders values. 
     */
    protected function setFlash($type, $message, $params = null)
    {
        Yii::app()->user->setFlash('AppManager.' . $type, 
                                   AppManagerModule::t($message, $params));
    }
}