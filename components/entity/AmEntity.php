<?php
/**
 * Base class for all entities. 
 * Entity represents a part of application or application itself.
 * Implements Composite design pattern.
 * 
 * Properties that available through AmParser.
 * @property string $author
 * @property string $description
 * @property string $summary
 * @property string $link
 * @property string $fileName
 * @property string $className
 * @see AmParser
 */
class AmEntity extends AmModel
{
    /**
     * @var string Yii alias to the entity file or directory.
     */
    protected $id;
    /**
     * @var AmEntity 
     */
    protected $parent;
    /**
     * @var string human-readable title. 
     */
    protected $title;
    /**
     * @var AmOptions class properties with the configue values. 
     */
    protected $options;
    /**
     * @var string name as it appears in the configue.
     */
    protected $name;
    /**
     * @var string Yii alias to the entity class.
     */
    protected $fullClassName;
    
    /**
     * @var string absolute path to the entity file or directory. 
     */
    private $_path;
    /**
     * @var string absolute path to the file. 
     */
    private $_fileName;
    /**
     * @var AmParser 
     */
    private $_parser;
    /**
     * @var array class attributes that were parsed. 
     */
    private $_attributes;
    /**
     * @var AmConfigHelper 
     */
    private $_configHelper;
    
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if(isset($this->_attributes[$name])) {
			return $this->_attributes[$name];
        } elseif (property_exists($this->getParser(), $name)) {
            return $this->_attributes[$name] = $this->getParser()->$name;
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
        $this->id = $this->getParent()->getId() . '.' . $id;
        return $this;
    }
    
    /**
     * @return AmEntity
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * @param AmEntity $parent
     * @return AmEntity
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * Adds the entity to the configue.
     * @return bool 
     */
    public function activate() 
    {
        if (!$this->canActivate()) {
            return false;
        }
        return $this->getConfigHelper()->activate();
    }
    
    /**
     * Removes from the configue.
     * @return bool 
     */
    public function deactivate() 
    {
        if (!$this->canDeactivate()) {
            return false;
        }
        return $this->getConfigHelper()->deactivate();
    }
    
    /**
     * Saves the entity and all its options.
     * @return bool 
     */
    public function save() 
    {
        if (!$this->canUpdate() || !$this->validate()) {
            return false;
        }
        $config = $this->getConfigHelper()->update(); 
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
        return $this->getConfigHelper()->restore();
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
        return !$this->getConfigHelper()->isEmpty();
    }
    
    /**
     * Determines if the entity can BE activated.
     * @return bool
     */
    public function canActivate()
    {
        return (!$this->getIsActive() && $this->getConfigHelper()->isWritable());
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
        return ($this->getIsActive() && $this->getConfigHelper()->isWritable());
    }
    
    /**
     * @return bool 
     */
    public function canRestore()
    {
        return ($this->canUpdate() && $this->getConfigHelper()->isChanged());
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
            $this->name = $this->getConfigHelper()->getName();
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
     * @param string $name
     * @return AmEntity
     */
    public function setFullClassName($name)
    {
        $this->fullClassName = $name; 
        return $this;
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
            $options->setConfig($this->getConfig());
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
     * Gets a configue manager.
     * @return AmConfigHelper
     */
    protected function getConfigHelper()
    {
        if (null === $this->_configHelper) {
            $this->_configHelper = new AmConfigHelper($this);
        }
        return $this->_configHelper;
    }
    
    /**
     * Gets the configue.
     * @return AmConfig
     */
    protected function getConfig()
    {
        return $this->getConfigHelper()->get();
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
     * Finds a child.
     * @param string $id
     * @return AmEntity|null
     */
    public function getChild($id)
    {
        return null;
    }
    
    /**
     * @return CArrayDataProvider
     */
    public function getChildrenProvider()
    {
        return new CArrayDataProvider($this->getChildren());
    }
    
    /**
     * @return AmEntity[]
     */
    public function getChildren()
    {
        return array();
    }
}