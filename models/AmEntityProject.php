<?php

class AmEntityProject extends AmEntityComposite
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }
    
    protected function createChild($id)
    {
        if ('application' === $id) {
            $id = 'app';
        }
        $entityClass = 'AmEntity' . ucfirst($id);
        return new $entityClass($id, $this);
    }
}