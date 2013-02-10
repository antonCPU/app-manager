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
        $results = parent::scan();
        $index   = array_search($this->getClassName(), $results);
        if (false !== $index) {
            unset($results[$index]);
        }
        return $results;
    }
    
    protected function createChild($id)
    {
        $entityClass = 'AmEntity' . ucfirst($id);
        return new $entityClass($id, $this);
    }
}
