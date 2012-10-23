<?php

class AmEntityApp extends AmEntityModule
{
    public function getId()
    {
        return 'application';
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
    
    public function getSection()
    {
        return null;
    }
}
