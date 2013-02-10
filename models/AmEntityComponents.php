<?php

class AmEntityComponents extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Components');
    }
    
    protected function createChild($id)
    {
        $id = $this->getId() . '.' . $id;
        $entity = new AmEntityComponent($id, $this);
        return $entity->isCorrect() ? $entity : null;
    }
}
