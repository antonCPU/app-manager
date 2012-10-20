<?php
/**
 * Works with the Yii config.
 */
class AmConfig extends AmNode
{
    /**
     * @var string full path
     * @see CConfig::loadFromFile()
     */
    protected $location;
    
    /**
     * @param string $location 
     */
    public function __construct($location=null)
    {
        $this->loadFromFile($location);
    }
    
    /**
     * @param string $configFile
     */
    public function loadFromFile($configFile)
    {
        $this->setLocation($configFile);
        parent::loadFromFile($configFile);
    }
    
    /**
     * Updates config file.
     * @throws CException if config file is not writable.
     * @return bool 
     */
    public function save()
    {
        if (!$this->isWritable()) {
            throw new CException(AppManagerModule::t('Config file is not writable.'));
        }
        return (bool)@file_put_contents($this->getLocation(), 
                                        (string)$this->getWriter());
    }
    
    /**
     * @param string $location 
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
    
    /**
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }
    
    /**
     * @return bool 
     */
    public function isWritable()
    {
        return is_writable($this->location);
    }
    
    /**
     * Gets helper that is able to generate a config file.
     * @return AmWriter 
     */
    protected function getWriter()
    {
        Yii::import('appManager.components.writer.*');
        return new AmWriter($this);
    }
}
