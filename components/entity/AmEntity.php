<?php
/**
 * Base class for all entities. 
 * Entity represents a part of application or application itself.
 * Implements Composite design pattern.
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
     * @var string 
     */
    protected $path;
    
    public function __construct($id, $parent = null)
    {
        $this->id     = $id;
        $this->parent = $parent;
        
        $this->attachBehaviors($this->behaviors());
    }
    
    /**
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AmEntity
     */
    public function getParent()
    {
        return $this->parent;
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
     * Creates title from id.
     * @return string 
     */
    protected function createTitle()
    {
        $id = explode('.', $this->getId());
        return ucfirst(array_pop($id));
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
     * @return AmEntity[]
     */
    public function getChildren()
    {
        return array();
    }
    
    /**
     * @return CDataProvider
     */
    public function getChildrenProvider()
    {
        return new CArrayDataProvider($this->getChildren());
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
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }
}