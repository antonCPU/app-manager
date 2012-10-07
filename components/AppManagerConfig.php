<?php
/**
 * Works with the Yii config.
 */
class AppManagerConfig extends AppManagerNode
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
     * @return bool 
     */
    public function save()
    {
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
     * Gets helper that is able to generate a config file.
     * @return AppManagerWriter 
     */
    protected function getWriter()
    {
        Yii::import('appManager.components.writer.*');
        return new AppManagerWriter($this);
    }
}
