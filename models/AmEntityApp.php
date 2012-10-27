<?php

class AmEntityApp extends AmEntityModule
{
    public function rules()
    {
        return array();
    }
    
    public function canUpdate()
    {
        return ($this->getConfig()->isWritable());
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
}
