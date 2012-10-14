<?php

class AmEntityModule extends AmEntity
{
    protected function formClassName($path)
    {
        return ucfirst(parent::formClassName($path)) . 'Module';
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
    
    protected function getConfigSection()
    {
        return 'modules';
    }
}
