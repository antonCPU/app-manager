<?php

class AmEntityComposite extends AmEntity
{
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
    
    protected function createSearch($section)
    {
        $this->section = $section;
        return new AmSearch($this->getPath() . DIRECTORY_SEPARATOR . $section);
    }
}
