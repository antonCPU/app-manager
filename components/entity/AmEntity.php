<?php
/**
 * Base class for all entities. 
 * Entity represents a part of application or application itself.
 * Implements Composite design pattern.
 */
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
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the entity unique identifier.
     * @param string $id Yii alias to the source (file or directory).
     * @return AmEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
        return $config->save();
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
    
    /**
     * Gets a name for the configue file.
     * @return string
     */
    public function getName()
    {
        if (null === $this->name) {
            $this->name = $this->getConfig()->getName();
        }
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return AmEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Generates a name for the configue file.
     * @return string
     */
    public function getDefaultName()
    {
        $id = $this->getId();
        $tmp = explode('.', $id);
        return lcfirst(array_pop($tmp));
    }
    
    /**
     * Verifies if current name is a default.
     * @return bool
     */
    public function getIsDefaultName()
    {
        return ($this->getName() === $this->getDefaultName());
    }
    
    /**
     * Gets well formatted title.
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
     * Creates title from a related file.
     * @return string 
     */
    protected function createTitle()
    {
        return ucfirst(basename($this->getFileName(), '.php'));
    }
    
    /**
     * Gets full Yii alias to the class.
     * @return string
     */
    public function getFullClassName()
    {
        if (null === $this->fullClassName) {
            $this->fullClassName = $this->resolveFullClassName();
        }
        return $this->fullClassName;
    }
    
    /**
     * Finds a full Yii alias for the class.
     * @return string
     */
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
     * Creates a class name from the source name.
     * @param string $name
     * @return string 
     */
    protected function formClassName($path)
    {
        return ucfirst(basename($path, '.php'));
    }
    
    /**
     * Gets an absolute path to the source (file or directory).
     * @return string 
     */
    protected function getPath()
    {
        if (null === $this->_path) {
            $this->_path = $this->resolvePath();
        }
        return $this->_path;
    }

    /**
     * Determines an absolute path.
     * @return string
     */
    protected function resolvePath()
    {
        return Yii::getPathOfAlias($this->getId());
    }
    
    /**
     * Gets an absolute path to the entity class.
     * @return string 
     */
    public function getFileName()
    {
        if (null === $this->_fileName) {
            $this->_fileName = $this->resolveFileName();
        }
        return $this->_fileName;
    }
    
    /**
     * Determines an absolute file name.
     * @return string
     */
    protected function resolveFileName()
    {
        return Yii::getPathOfAlias($this->getFullClassName()) . '.php';
    }
    
    /**
     * Gets attributes for editing.
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
     * Sets options from input data.
     * @param array $options 
     */
    public function setOptions($options)
    { 
        $this->getOptions()->attributes = $options;
    }
    
    /**
     * Gets a parser for the class attributes.
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
     * Gets a configue manager (helper).
     * @return AmConfigEntity
     */
    protected function getConfig()
    {
        if (null === $this->_config) {
            $this->_config = new AmConfigEntity($this);
        }
        return $this->_config;
    }
    
    /**
     * Gets name of the configue section.
     * @return string|null 
     */
    public function getConfigSection()
    {
        return null;
    }
    
    /**
     * Gets the entity children.
     * @param string $type children category.
     * @return CArrayDataProvider|null
     */
    public function getChildren($type = null)
    {
        return null;
    }
}