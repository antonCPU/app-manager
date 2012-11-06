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
     * @var AmOptions class properties with the config values. 
     */
    protected $options;
    /**
     * @var string name as it appears in the config.
     */
    protected $name;
    /**
     * @var string
     */
    protected $defaultName;
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
        }
        $method = 'get' . ucfirst($name);
        if (method_exists($this->getParser(), $method)) {
            return $this->_attributes[$name] = $this->getParser()->$method();
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
     * Adds the entity to the config.
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
     * Removes from the config.
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
        $helper  = $this->getConfigHelper()->update(); 
        if (!$this->getOptions()->updateConfig()) {
            return false;
        }
        return $helper->save();
    }
    
    /**
     * Restores options.
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
     * @return bool if the entity has details.
     */
    public function canView()
    {
        return $this->isCorrect();
    }
    
    /**
     * @return bool if the entity could be listed.
     */
    public function canList()
    {
        return false;
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
     * Gets a name for the config file.
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
     * Generates a name for the config file.
     * @return string
     */
    public function getDefaultName()
    {
        if (null === $this->defaultName) {
            $id = $this->getId();
            $tmp = explode('.', $id);
            $name = array_pop($tmp);
            $name[0] = strtolower($name[0]);
            $this->defaultName = $name;
        }
        return $this->defaultName;
    }
    
    /**
     * @param string $name
     * @return AmEntity
     */
    public function setDefaultName($name)
    {
        $this->defaultName = $name;
        return $this;
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
    
    public function isCorrect()
    {
        return (bool)$this->getFullClassName();
    }
    
    /**
     * Finds a full Yii alias for the class.
     * @return string
     */
    protected function resolveFullClassName()
    {
        return AmSearchEntity::resolve($this);
    }
    
    /**
     * Gets list of patterns for searching a file that contains entity.
     * @return array
     */
    public function getSearchPattern()
    {
        return array('*.php');
    }
    
    /**
     * Gets entity base class.
     * @return string|null
     */
    public function getBaseClass()
    {
        return null;
    }
    
    /**
     * Gets an absolute path to the source (file or directory).
     * @return string 
     */
    public function getPath()
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
            $this->options = $options->setEntity($this);
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
    public function getParser()
    { 
        if (null === $this->_parser) {
            $this->_parser = new AmParser($this->getFileName());
        }
        return $this->_parser;
    }
    
    /**
     * Gets a config manager.
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
     * Gets the config.
     * @return AmConfig
     */
    public function getConfig()
    {
        return $this->getConfigHelper()->get();
    }
    
    /**
     * Gets name of the config section.
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
    
    /**
     * Gets list of options that should not be visible.
     * @return array list of string names.
     */
    public function getExcludeOptions()
    {
        return array();
    }
}