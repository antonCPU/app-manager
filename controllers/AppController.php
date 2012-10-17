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
        $model = new AmSettingsForm;
        if ($data = $this->getPost('AmSettingsForm')) {
            $model->attributes = $data;
            if ($model->save()) {
                $this->setFlash('success', 'Settings have been saved.');
            }
        }
        
        $this->render('settings', array(
            'model' => $model,
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
