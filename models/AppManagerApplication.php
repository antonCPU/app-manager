<?php

class AppManagerApplication extends AppManagerEntity
{
    /**
     * Initialization.
     * @param string $path full path or with aliases.
     */
    public function __construct($path = null)
    {
        $this->setPath(Yii::getPathOfAlias('system.web.CWebApplication') . '.php');
    }
    
    /**
     * Loads main config.
     * Creates an empty if does not exist.
     * @return AppManagerConfig
     */
    protected function loadConfig()
    {
        return $this->loadConfigSection();
    }
}
