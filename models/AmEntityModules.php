<?php

class AmEntityModules extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Modules');
    }
    
    protected function createChild($id)
    {
        $child = new AmEntityModule($this->getId() . '.' . $id, $this);
        return $child->isCorrect() ? $child : null;
    } 
}
