<?php

class AmSearchCoreModule extends AmSearchModule
{
    public function findById($id)
    {
        $components = $this->scan();
        $name = array_search($id, $this->scan());
        $entity = $this->createEntity($id);
        return $entity->setName($name);
    }

    protected function scan()
    {
        return array(
            'gii' => 'system.gii.GiiModule',
        );
    }
    
    protected function createEntities($results)
    {
        $entities = array();
        foreach ($results as $name => $id)
        {
            $entity = $this->createEntity($id);
            $entity->setName($name);
            $entities[] = $entity;
        }
        return $entities;
    }
}
