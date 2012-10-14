<?php

class AmSearchComponent extends AmSearch
{
    protected function createEntity($id)
    {
        $entity = new AmEntityComponent();
        return $entity->setId($id);
    }
}
