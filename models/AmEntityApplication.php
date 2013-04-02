<?php

class AmEntityApplication extends AmEntityModule
{
    public function behaviors()
    {
        return array(
            'options' => array(
                'class'   => 'appManager.components.entity.behaviors.AmOptionsBehavior',
            ),
            'class' => array(
                'class' => 'appManager.components.entity.behaviors.AmClassBehavior',
                'fullClassName' => 'system.web.CWebApplication',
            ),
        );
    }
    
    public function getConfig()
    {
        return AppManagerModule::config();
    }
    
    public function getTitle()
    {
        return AppManagerModule::t('Application');
    }
}
