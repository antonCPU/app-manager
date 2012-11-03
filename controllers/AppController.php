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
        $entity = $this->getBaseEntity();
        
        if ($options = $this->getPost('AmOptions')) {
            $entity->options    = $options;
            
            if ($entity->save()) {
                $this->setEntityFlash('success', 'Settings has been updated.');
            } else {
                $this->setEntityFlash('error', 'Unable to update. Incorrect input.');
            }
        }

        $this->render('settings', array(
            'entity' => $entity,
        ));
    }
    
    public function getMenu()
    {
        $id = $this->getBaseEntity()->getId();
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
