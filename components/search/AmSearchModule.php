<?php

class AmSearchModule extends AmSearch
{
    protected function createEntity($id)
    {
        $entity = new AmEntityModule();
        return $entity->setId($id);
    }
}
