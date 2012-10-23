<?php

class AmEntityModules extends AmEntitySearch
{
    protected function createChild($id)
    {
        $entity = new AmEntityModule;
        return $entity->setParent($this)->setId($id);
    } 
}
