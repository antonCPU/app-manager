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
        $this->render('settings', array(
            'model' => $this->getModel(),
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
