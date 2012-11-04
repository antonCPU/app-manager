<?php

class AmEntityExtensions extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Extentions');
    }
    
    public function canList()
    {
        return true;
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityComponent;
        return $entity->setParent($this)->setId($id);
    }
}
