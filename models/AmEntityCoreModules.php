<?php

class AmEntityCoreModules extends AmEntityComposite
{
    public function getChild($id)
    {  
        return $this->createChild();
    }
    
    public function getChildren()
    {
        return array($this->createChild());
    }
    
    protected function createChild($id = 'gii')
    {
        $entity = new AmEntityModule('system.modules.gii', $this);
        $entity->config->setDefaultName('gii');
        $entity->class->setFullClassName('system.gii.GiiModule');
        return $entity;
    }
}
