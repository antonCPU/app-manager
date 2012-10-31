<?php

class AmEntityModules extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Modules');
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityModule;
        return $entity->setParent($this)->setId($id);
    } 
}
