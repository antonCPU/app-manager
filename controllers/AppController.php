<?php
/**
 * Handles requests for entities that belong to the application.
 */
class AppController extends AmEntityController
{
    protected $title = 'App';
    public $defaultAction = 'settings';
    
    protected function createModel()
    {
        return new AmEntityApp;
    }
    
    public function actionSettings()
    { 
        $this->baseEntity = $this->getEntity();
        $this->actionUpdate($this->getEntity()->getId());
    }
    
    public function getMenu()
    {
        $id = $this->getBase()->getId();
        return array_merge(parent::getMenu(), array(
            array(
                'label'  => AppManagerModule::t('Extensions'), 
                'url'    => array('list', 'id' => $id . '.extensions'), 
                'active' => $this->isSection('extensions'),
            ),
            array(
                'label'  => AppManagerModule::t('Settings'), 
                'url'    => array('settings', 'id' => $id), 
                'active' => ('settings' === $this->action->id),
            ),
        ));
    }
}
