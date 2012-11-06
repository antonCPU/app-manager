<?php

class AmEntityCoreModules extends AmEntityModules
{
    public function getChild($id)
    {  
        $modules = $this->scan();
        return $this->createChild($modules['gii'], 'gii');
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
        $entity->setDefaultName($name);
        $entity->setFullClassName($this->getParent()->getId() . '.' . $id);
        return $entity;
    }
}
