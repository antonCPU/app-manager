<?php

class AmEntityModule extends AmEntityComposite
{
    public function behaviors()
    {
        return array(
            'config' => array(
                'class'   => 'appManager.components.entity.AmConfigBehavior',
                'section' => 'modules',
            ),
            'class' => array(
                'class' => 'appManager.components.entity.AmClassBehavior',
                'searchPatterns' => array('*Module.php'),
                'baseClass' => 'CModule',
            ),
        );
    }
    
    protected function createTitle()
    {
        return str_replace('Module', '', parent::createTitle());
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntity' . ucfirst($id);
        $entity = new $entityClass;
        return $entity->setParent($this)->setId($id);
    }
}
