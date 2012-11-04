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
    
    public function canView()
    {
        return false;
    }
    
    public function canUpdate()
    {
        return false;
    }
    
    public function canActivate()
    {
        return false;
    }
    
    public function canDeactive()
    {
        return false;
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityModule;
        return $entity->setParent($this)->setId($id);
    } 
}
