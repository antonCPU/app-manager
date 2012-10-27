<?php

class AmEntityCoreModules extends AmEntityModules
{
    public function getChild($id)
    {  
        $modules = $this->scan();
        $name = array_search($id, $modules);
        if (false === $name) {
            return null;
        }
        return $this->createChild($id);
    }
    
    protected function scan()
    {
        return array(
            'gii' => 'gii.GiiModule',
        );
    }
    
    protected function createChildren($results)
    { 
        $entities = array();
        foreach ($results as $name => $id) {
            $entities[] = $this->createChild($id, $name);
        }
        return $entities;
    }
    
    protected function createChild($id, $name = null)
    {
        $entity = parent::createChild($id);
        $entity->setName($name);
        $entity->setFullClassName($id);
        return $entity;
    }
}
