<?php

class AmEntityComposite extends AmEntity
{
    protected $section;
    
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
        $this->section = $section;
        return new AmSearch($this->getPath() . DIRECTORY_SEPARATOR . $section);
    }
}
