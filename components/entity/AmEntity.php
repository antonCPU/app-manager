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
 * @see AmClassInfo
 */
abstract class AmEntity extends AmModel
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
     * @var string name as it appears in the config.
     */
    protected $name;
    /**
     * @var string
     */
    protected $defaultName;

    protected $path;
    
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
        if ($parent = $this->getParent()) {
            $id = $parent->getId() . '.' . $id;
        }
        $this->id = $id;
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
     * Gets an absolute path to the source (file or directory).
     * @return string 
     */
    public function getPath()
    {
        if (null === $this->path) {
            $this->path = Yii::getPathOfAlias($this->getId());
        }
        return $this->path;
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