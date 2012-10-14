<?php

class AmSearchComponent extends AmSearch
{
    protected function createEntity($id)
    {
        return new AmEntityComponent($id);
    }
}
