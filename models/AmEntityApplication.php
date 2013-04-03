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
    
    /**
     * @return AmConfig
     */
    public function getConfig()
    {
        return AppManagerModule::config();
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return AppManagerModule::t('Application');
    }
    
    /**
     * @return bool
     */
    public function canUpdate()
    {
        return $this->getConfig()->isWritable();
    }
    
    /**
     * @return bool
     */
    public function update()
    {
        if ($this->canUpdate()) {
            return $this->getConfig()->save();
        }
        return false;
    }
}
