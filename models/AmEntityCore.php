<?php

class AmEntityCore extends AmEntityComposite
{
    public function getExtensions()
    {
        return null;
    }
    
    public function findById($id)
    {
        $section = 'components';
        $entity = $this->createSearch($section)->findById($id);
        if (!$entity) {
            $section = 'modules';
            $entity = $this->createSearch($section)->findById($id);
        }
        return $entity;
    }
    
    protected function createSearch($section)
    {
        $type = ('components' === $section) ? 'Component' : 'Module'; 
        $className = 'AmSearchCore' . $type;
        $this->section = $section;
        return new $className('system');
    }
    
}
