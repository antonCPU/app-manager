<?php

class AmEntity extends AmModel
{
    protected $id;
    protected $title;
    protected $options;
    protected $attributes;
    protected $name;
    protected $fullClassName;
    protected $isActive;
    
    private $_path;
    private $_fileName;
    private $_parser;
    private $_config;
    
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
        if (!$this->canActivate()) {
            return false;
        }
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
        if (!$this->canDeactivate()) {
            return false;
        }
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
        if (!$this->canUpdate() || !$this->validate()) {
            return false;
        }
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
        if (!$this->canRestore()) {
            return false;
        } 
        $config = $this->getConfig();
        $config->clear();
        $config->add('class', $this->getFullClassName());
        return $this->saveConfig();
    }
    
    /**
     * Validation rules.
     * @return array 
     */
    public function rules()
    {
        return array(
          array('name', 'required'),
        );
    }
    
    /**
     * @return bool 
     */
    public function getIsActive() 
    { 
        if (null === $this->isActive) {
            $this->isActive = (bool)$this->getConfig()->count();
        } 
        return $this->isActive;
    }
    
    /**
     * Determines if the entity can BE activated.
     * @return bool
     */
    public function canActivate()
    {
        return !$this->getIsActive();
    }
    
    /**
     * @return bool 
     */
    public function canDeactivate()
    {
        return $this->getIsActive();
    }
    
    /**
     * @return bool 
     */
    public function canUpdate()
    {
        return $this->getIsActive();
    }
    
    /**
     * @return bool 
     */
    public function canDelete()
    {
        return true;
    }
    
    /**
     * @return bool 
     */
    public function canRestore()
    {
        return ($this->canUpdate() && ($this->getConfig()->count() > 1));
    }
    
    /**
     * @return string
     * @see CButtonColumn 
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
            $this->name = $this->getDefaultName();
        }
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getDefaultName()
    {
        $id = $this->getId();
        $tmp = explode('.', $id);
        return lcfirst(array_pop($tmp));
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
            $this->_path = $this->resolvePath();
        }
        return $this->_path;
    }

    protected function resolvePath()
    {
        return Yii::getPathOfAlias($this->getId());
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
            if (!$this->normalizeConfig($config, $name)) {
                $config->add($name, array());
            } 
            $current = $config->itemAt($name);
        } 
        return $current;
    }
    
    protected function normalizeConfig($config, $name)
    {
        $key = $config->search($name);
        if (false !== $key) {
            $config->remove($key);
            $config->add($name, array(
                'class' => $this->getFullClassName(),
            ));
            return true;
        }
        return false;
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