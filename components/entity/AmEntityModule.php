<?php

class AmEntityModule extends AmEntity
{
    protected function formFileName($name)
    {
        return parent::formFileName(ucfirst($name) . 'Module');
    }
    
    public function getComponents()
    {
        $search = new AmSearchComponent($this->getPath() . '/components');
        return $search->perform();
    }
    
    public function getModules()
    {
        $search = new AmSearchModule($this->getPath() . '/modules');
        return $search->perform();
    }
    
    public function getExtensions()
    {
        $search = new AmSearchComponent($this->getPath() . '/extensions');
        return $search->perform();
    }
}
