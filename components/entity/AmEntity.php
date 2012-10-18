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
        return $this->getConfig()->activate();
    }
    
    /**
     * @return bool 
     */
    public function deactivate() 
    {
        if (!$this->canDeactivate()) {
            return false;
        }
        return $this->getConfig()->deactivate();
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
        $config = $this->getConfig()->update(); 
        if (!$this->getOptions()->updateConfig()) {
            return false;
        }
        return $this->getConfig()->save();
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
        return $this->getConfig()->restore();
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
            $this->isActive = !$this->getConfig()->isEmpty();
        } 
        return $this->isActive;
    }
    
    /**
     * Determines if the entity can BE activated.
     * @return bool
     */
    public function canActivate()
    {
        return (!$this->getIsActive() && $this->getConfig()->isWritable());
    }
    
    /**
     * @return bool 
     */
    public function canDeactivate()
    {
        return $this->canUpdate();
    }
    
    /**
     * @return bool 
     */
    public function canUpdate()
    {
        return ($this->getIsActive() && $this->getConfig()->isWritable());
    }
    
    /**
     * @return bool 
     */
    public function canRestore()
    {
        return ($this->canUpdate() && $this->getConfig()->isChanged());
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
            $this->name = $this->getConfig()->getName();
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
    
    public function getIsDefaultName()
    {
        return ($this->getName() === $this->getDefaultName());
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
            $file = $path . DIRECTORY_SEPARATOR . $className . '.php';
            if (is_file($file)) {
                $name .= '.' . $className;
            } elseif ($files = glob($path . '/*.php')) {
                $name .= '.' . basename($files[0], '.php');
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
            $options->setConfig($this->getConfig()->get());
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
            $this->_config = new AmConfigEntity($this);
        }
        return $this->_config;
    }
    
    /**
     * Gets name of the config section.
     * @return string 
     */
    public function getConfigSection()
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
        return ucfirst(basename($path, '.php'));
    }
}