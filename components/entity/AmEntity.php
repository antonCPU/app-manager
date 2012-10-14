<?php

class AmEntity extends AmModel
{
    protected $id;
    protected $title;
    protected $options;
    protected $attributes;
    protected $name;
    protected $fullClassName;
    
    private $_path;
    private $_fileName;
    private $_parser;
    private $_config;
    
    /**
     * Initialization.
     * @param string $id alias
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function __get($name)
    {
        if(isset($this->attributes[$name])) {
			return $this->attributes[$name];
        } elseif (property_exists($this->getParser(), $name)) {
            return $this->attributes[$name] = $this->getParser()->$name;
        } 
        return parent::__get($name);
    }
    
    /**
     * @return bool 
     */
    public function activate() 
    {
        $this->loadConfigSection()->add($this->getName(), array(
            'class' => $this->getFullClassName(),
        ));
        return $this->saveConfig();
    }
    
    /**
     * @return bool 
     */
    public function deactivate() 
    {
        $this->loadConfigSection()->remove($this->getName());
        return $this->saveConfig();
    }
    
    /**
     * Completely deletes the entity.
     * @return bool
     */
    public function delete() 
    {
        if (!$this->canDelete()) {
            return false;
        }
    }
    
    /**
     * Saves entity and all options.
     * @return bool 
     */
    public function save() 
    {
        $config = $this->getConfig(); 
        $config->add('class', $this->getFullClassName());
        if (!$this->getOptions()->updateConfig()) {
            return false;
        }
        return $this->saveConfig();
    }
    
    /**
     * Restores options and the class name to defaults.
     * @return bool
     */
    public function restore()
    {
        $config = $this->getConfig();
        $config->clear();
        $config->add('class', $this->getFullClassName());
        return $this->saveConfig();
    }
    
    /**
     * @return string
     * @see CButtonColumn 
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }
    
    /**
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        if (null === $this->name) {
            $id = $this->getId();
            $tmp = explode('.', $id);
            $this->name = lcfirst(array_pop($tmp));
        }
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getTitle() 
    {
        if (null === $this->title) {
            $this->title = $this->createTitle();
        }
        return $this->title;
    }
  
    /**
     * Forms title.
     * @return string 
     */
    protected function createTitle()
    {
        return ucfirst(basename($this->getFileName(), '.php'));
    }
    
    public function getFullClassName()
    {
        if (null === $this->fullClassName) {
            $this->fullClassName = $this->resolveFullClassName();
        }
        return $this->fullClassName;
    }
    
    protected function resolveFullClassName()
    {
        $name = $this->getId();
        $path = $this->getPath();
        if (!is_file($path . '.php')) {
            $className = $this->formClassName($path);
            $path .= DIRECTORY_SEPARATOR . $className . '.php';
            if (is_file($path)) {
                $name .= '.' . $className;
            }
        } 
        return $name;
    }
    
    /**
     * @return string 
     */
    protected function getPath()
    {
        if (null === $this->_path) {
            $this->_path = Yii::getPathOfAlias($this->getId());
        }
        return $this->_path;
    }

    /**
     * @return string 
     */
    public function getFileName()
    {
        if (null === $this->_fileName) {
            $this->_fileName = $this->resolveFileName();
        }
        return $this->_fileName;
    }
    
    protected function resolveFileName()
    {
        return Yii::getPathOfAlias($this->getFullClassName()) . '.php';
    }
    
    /**
     * Sets options from input data.
     * @param array $options 
     */
    public function setOptions($options)
    { 
        $this->getOptions()->attributes = $options;
    }
    
    /**
     * @return AppManagerOptions 
     */
    public function getOptions() 
    { 
        if (null === $this->options) {
            $options = new AmOptions;
            $options->setParser($this->getParser());
            $options->setConfig($this->getConfig());
            $this->options = $options;
        }
        return $this->options;
    }
    
    /**
     * @return AppManagerOptions 
     */
    public function getOptionsProvider()
    {
        return $this->getOptions()->getProvider();
    }
    
    /**
     * @return AppManagerParser 
     */
    protected function getParser()
    { 
        if (null === $this->_parser) {
            $this->_parser = new AmParser($this->getFileName());
        }
        return $this->_parser;
    }
    
    /**
     * @return AppManagerConfig 
     */
    protected function getConfig()
    {
        if (null === $this->_config) {
            $this->_config = $this->loadConfig();
        }
        return $this->_config;
    }
    
        /**
     * Loads main config.
     * Creates an empty if does not exist.
     * @return AppManagerConfig
     */
    protected function loadConfig()
    {
        $config  = $this->loadConfigSection();
        $name    = $this->getName();
        $current = $config->itemAt($name);
        if (null === $current) {
            $key = $config->search($name);
            if (false !== $key) { //normalize config
                $config->remove($key);
                $config->add($name, array(
                    'class' => $this->getFullClassName(),
                ));
            } else {
                $config->add($name, array());
            }
            $current = $config->itemAt($name);
        } 
        return $current;
    }
    
    /**
     * @return bool 
     */
    protected function saveConfig()
    {
        return AppManagerModule::config()->save();
    }
    
    /**
     * Gets value from config.
     * @param string $name
     * @return mixed 
     */
    protected function getConfigValue($name)
    {
        if ($config = $this->getConfig()) {
            return $config->itemAt($name);
        }
        return null;
    }
    
    /**
     * Loads config only for current settings section.
     * @return AppManagerConfig 
     */
    protected function loadConfigSection()
    { 
        return AppManagerModule::config($this->getConfigSection());
    }
    
    /**
     * Gets name of the config section.
     * @return string 
     */
    protected function getConfigSection()
    {
        return null;
    }
    
    /**
     * Forms class name.
     * @param string $name
     * @return string 
     */
    protected function formClassName($path)
    {
        return basename($path, '.php');
    }
}