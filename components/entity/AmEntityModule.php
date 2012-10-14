<?php

class AmEntityModule extends AmEntity
{
    protected $section;
    
    protected function formClassName($path)
    {
        return ucfirst(parent::formClassName($path)) . 'Module';
    }
    
    public function getComponents()
    {
        return $this->createSearch('components')->perform();
    }
    
    public function getModules()
    {
        return $this->createSearch('modules')->perform();
    }
    
    public function getExtensions()
    {
        return $this->createSearch('extensions')->perform();
    }
    
    public function findById($id)
    { 
        $tmp = str_replace($this->getId() . '.', '', $id);
        $tmp = explode('.', $tmp);
        $section = $tmp[0];
        return $this->createSearch($section)->findById($id);
    }
    
    public function getSection()
    {
        return $this->section;
    }
    
    protected function createSearch($section)
    {
        $sections = $this->getSearchSections(); 
        $className = 'AmSearch' . ucfirst($sections[$section]);
        $this->section = $section;
        return new $className($this->getPath() . DIRECTORY_SEPARATOR . $section);
    }
    
    protected function getSearchSections()
    {
        return array(
            'components'  => 'component',
            'modules'     => 'module',
            'extensions'  => 'component',
        );
    }
    
    protected function getConfigSection()
    {
        return 'modules';
    }
}
