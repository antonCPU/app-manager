<?php

class AmEntityComponents extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Components');
    }
    
    protected function createChild($id)
    {
        $entity = new AmEntityComponent($this->formChildId($id), $this);
        return $entity->isCorrect() ? $entity : null;
    }
}
