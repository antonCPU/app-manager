<?php

class AmEntityExtensions extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Extentions');
    }
    
    protected function createChild($id)
    {
        $id = $this->getId() . '.' . $id;
        $entity = new AmEntityComponent($id, $this);
        return $entity->isCorrect() ? $entity : null;
    }
}
