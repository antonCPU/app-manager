<?php

class AmEntitySystem extends AmEntityComposite
{
    public function getTitle()
    {
        return AppManagerModule::t('Core');
    }
    
    public function getId()
    {
        return 'system';
    }
    
    public function scan()
    {
        return array(
            'components',
            'modules',
        );
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntitySystem' . ucfirst($id);
        return new $entityClass($this->formChildId($id), $this);
    }
}
