<?php

class AmEntityModules extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Modules');
    }
    
    public function canList()
    {
        return true;
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityModule;
        return $entity->setParent($this)->setId($id);
    } 
}
