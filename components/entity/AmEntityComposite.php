<?php

class AmEntityComposite extends AmEntity
{
    public function getChildren()
    {
        $results = array();
        if ($results = $this->scan()) {
            $results = $this->createChildren($results);
        }
        return $results;
    }
    
    protected function scan()
    {
        $results = scandir($this->getPath());
        unset($results[0], $results[1]);
        foreach ($results as &$result) {
            $result = basename($result, '.php');
        }
        return $results;
    }
    
    protected function createChildren($results)
    {
        $entities = array();
        foreach ($results as $result) {
            $entities[] = $this->createChild($result);
        }
        return $entities;
    }
    
    public function getChildrenProvider()
    {
        return new CArrayDataProvider($this->getChildren(), array(
            'sort'=>array(
                'defaultOrder'=>'isActive DESC',
            ),
        ));
    }
    
    public function getChild($id)
    { 
        $parts = explode('.', $id);
        $childId = array_shift($parts);
        $child = $this->createChild($childId);
        if ($parts) {
            $id = implode('.', $parts);
            $child = $child->getChild($id);
        }
        return $child;
    }
    
    protected function createChild($id)
    {
        $entity = new self;
        return $entity->setParent($this)->setId($id);
    }
}
