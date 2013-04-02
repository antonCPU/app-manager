<?php

class AmEntityApplication extends AmEntityModule
{
    public function behaviors()
    {
        return array(
            'config' => array(
                'class'   => 'appManager.components.entity.behaviors.AmConfigBehavior',
            ),
            'class' => array(
                'class' => 'appManager.components.entity.behaviors.AmClassBehavior',
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
