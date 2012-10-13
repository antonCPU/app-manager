<?php

class AmSearchModule extends AmSearch
{
    protected function createEntity($id)
    {
        return new AmEntityModule($id);
    }
}
