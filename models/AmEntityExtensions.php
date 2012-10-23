<?php

class AmEntityExtensions extends AmEntitySearch
{
    protected function createChild($id)
    {
        $entity = new AmEntityComponent;
        return $entity->setParent($this)->setId($id);
    }
}
