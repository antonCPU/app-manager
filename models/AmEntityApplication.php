<?php

class AmEntityApplication extends AmEntityModule
{
    public function behaviors()
    {
        return array(
            'config' => array(
                'class'   => 'appManager.components.entity.AmConfigBehavior',
            ),
            'class' => array(
                'class' => 'appManager.components.entity.AmClassBehavior',
                'fullClassName' => 'system.web.CWebApplication',
            ),
        );
    }
    
    public function isActive()
    {
        return true;
    }
    
    public function getTitle()
    {
        return AppManagerModule::t('Application');
    }
}
