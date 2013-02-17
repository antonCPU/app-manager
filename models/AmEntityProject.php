<?php

class AmEntityProject extends AmEntityComposite
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }
    
    public function scan()
    {
        return array(
            'application',
            'system',
        );
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntity' . ucfirst($id);
        return new $entityClass($id, $this);
    }
}