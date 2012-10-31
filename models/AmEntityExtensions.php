<?php

class AmEntityExtensions extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Extentions');
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityComponent;
        return $entity->setParent($this)->setId($id);
    }
}
