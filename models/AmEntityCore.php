<?php

class AmEntityCore extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Core');
    }
    
    public function getId()
    {
        return 'system';
    }
    
    public function canView()
    {
        return false;
    }
    
    public function getChild($id)
    {
        $id = str_replace($this->getId() . '.', '', $id);
        return parent::getChild($id);
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntityCore' . ucfirst($id);
        $entity = new $entityClass;
        return $entity->setParent($this)->setId($id);
    }
}
