<?php

class AmEntityProject extends AmEntityComposite
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }
    
    protected function createChild($id)
    {
        $name = ('core' === $id) ? 'Core' : 'App';
        $entityClass = 'AmEntity' . $name;
        return new $entityClass($id, $this);
    }
}