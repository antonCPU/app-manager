<?php

class AmEntityModule extends AmEntityComposite
{
    protected function createTitle()
    {
        return str_replace('Module', '', parent::createTitle());
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntity' . ucfirst($id);
        $entity = new $entityClass;
        return $entity->setParent($this)->setId($id);
    }
    
    public function getConfigSection()
    {
        return 'modules';
    }
    
    public function getSearchPattern()
    {
        return array('*Module.php');
    }
    
    public function getBaseClass()
    {
        return 'CModule';
    }
}
