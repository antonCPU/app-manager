<?php

class AmEntityModules extends AmEntityComposite
{
    protected function createChild($id)
    {
        $entity = new AmEntityModule;
        return $entity->setParent($this)->setId($id);
    } 
}
