<?php

class AmEntityApp extends AmEntityModule
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
                'fullClassName' => 'system.web.CWebApplication',
            ),
        );
    }
    
    public function getTitle()
    {
        return AppManagerModule::t('App');
    }
    
    public function getName()
    {
        return null;
    }
    
    public function getDefaultName()
    {
        return null;
    }
    
    public function rules()
    {
        return array();
    }
    
    public function getIsActive()
    {
        return true;
    }
    
    public function canActivate() 
    {
        return false;
    }
    
    public function canDeactivate()
    {
        return false;
    }
    
    public function canRestore()
    {
        return false;
    }
    
    public function canUpdate()
    {
        return $this->isWritable();
    }
    
    public function canView()
    {
        return false;
    }
    
    public function getId()
    {
        return 'application';
    }
    
    public function getFullClassName()
    {
        return 'system.web.CWebApplication';
    }
    
    public function getChild($id)
    {
        $id = str_replace($this->getId() . '.', '', $id);
        return parent::getChild($id);
    }
    
    protected function resolvePath()
    {
        return Yii::getPathOfAlias('application');
    }
    
    public function getExcludeOptions()
    {
        return array(
            'controller',
            'components',
            'modules',
            'behaviors',
        );
    }
}
