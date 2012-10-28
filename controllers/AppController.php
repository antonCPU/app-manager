<?php
/**
 * Handles requests for entities that belong to the application.
 */
class AppController extends AmEntityController
{
    protected $title = 'App';
    
    protected function createModel()
    {
        return new AmEntityApp;
    }
    
    public function actionSettings()
    { 
        $entity = $this->getModel();
        
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
        $menu = parent::getMenu();
        $menu[] = array(
            'label'  => AppManagerModule::t('Settings'), 
            'url'    => array('settings'), 
            'active' => ('settings' === $this->action->id),
        );
        return $menu;
    }
}
