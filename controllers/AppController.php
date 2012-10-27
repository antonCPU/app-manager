<?php

class AppController extends AmEntityController
{
    protected $title = 'App';
    
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = new AmEntityApp;
        }
        return $this->model;
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
