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
    
    protected function scan()
    {
        $list = array('components', 'modules', 'extensions');
        $path = $this->getPath();
        
        $results = array();
        foreach ($list as $entity) {
            if (is_dir($path . '/' . $entity)) {
                $results[] = $entity;
            }
        }
        return $results;
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntity' . ucfirst($id);
        return new $entityClass($id, $this);
    }
}
