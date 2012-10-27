<?php

class AmEntityComponents extends AmEntityComposite
{
    protected function createChild($id)
    {
        $entity = new AmEntityComponent;
        return $entity->setParent($this)->setId($id);
    }
}
