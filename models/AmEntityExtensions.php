<?php

class AmEntityExtensions extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Extentions');
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityComponent($this->formChildId($id), $this);
        return $entity->isCorrect() ? $entity : null;
    }
}
