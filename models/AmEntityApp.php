<?php

class AmEntityApp extends AmEntityModule
{
    public function rules()
    {
        return array();
    }
    
    public function getIsActive()
    {
        return true;
    }
    
    public function canActivate() 
    {
        return false;
    }
    
    public function canDeactivate()
    {
        return false;
    }
    
    public function save() 
    {
        if (!$this->canUpdate()) {
            return false;
        } 
        if (!$this->getOptions()->updateConfig()) {
            return false;
        }
        return $this->getConfig()->save();
    }
    
    public function getId()
    {
        return 'application';
    }
    
    public function getFullClassName()
    {
        return 'system.web.CWebApplication';
    }
    
    public function getChild($id)
    {
        $id = str_replace($this->getId() . '.', '', $id);
        return parent::getChild($id);
    }
    
    protected function resolvePath()
    {
        return Yii::getPathOfAlias('application');
    }
    
    public function getConfig()
    {
        return AppManagerModule::config();
    }
    
    public function getExcludeOptions()
    {
        return array(
            'controller',
        );
    }
}
