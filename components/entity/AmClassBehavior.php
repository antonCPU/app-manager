<?php
/**
 * Properties availabe through AmClassInfo
 * @property string $author
 * @property string $summary
 * @property string $description
 * @property string $version
 * @property string $link
 */
class AmClassBehavior extends CBehavior
{
    public $searchPatterns = array('*.php');
    public $baseClass      = 'CComponent';
    
    protected $classInfo;
    protected $fileName;
    protected $fullClassName;

    /**
     * Checks whether the entity has a proper structure.
     * @return boolean
     */
    public function isCorrect()
    {
        return (bool)$this->getFullClassName();
    }
    
    /**
     * @return AmProperty[]
     */
    public function getProperties()
    {
        return $this->getClassInfo()->getProperties();
    }
    
    /**
     * Gets a parser for the class attributes.
     * @return AmClassInfo 
     */
    public function getClassInfo()
    { 
        if (null === $this->classInfo) {
            if ($file = $this->getFileName()) {
                $this->classInfo = new AmClassInfo($file);
            }
        }
        return $this->classInfo;
    }
    
    /**
     * Gets an absolute path to the entity class.
     * @return string 
     */
    public function getFileName()
    {
        if (null === $this->fileName) {
            $this->fileName = $this->resolveFileName();
        }
        return $this->fileName;
    }
    
    /**
     * Determines an absolute file name.
     * @return string
     */
    protected function resolveFileName()
    {
        if ($path = $this->getFullClassName()) {
            return Yii::getPathOfAlias($path) . '.php';
        }
        return null;
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
    
    public function setFullClassName($name)
    {
        $this->fullClassName = $name;
    }
    
    /**
     * Finds related to entity class.
     * @param AmEntity $entity
     * @return string|bool full alias of class or false.
     */
    public function resolveFullClassName()
    {
        $id = $this->getOwner()->getId();
        $file = $this->getOwner()->getPath() . '.php';
        if (!is_file($file)) {
			Yii::import($id . '.*');
            foreach ($this->searchPatterns as $rule) {
                if ($files = $this->getByRule($rule)) {
                    foreach ($files as $file) {
                        if ($this->checkParent($file)) {
                            return $id . '.' . basename($file, '.php');
                        }
                    }
                } 
            }
        } elseif ($this->checkParent($file)) {
            return $id;
        }
        return false;
    }
    
    /**
     * Searches files by certain rule.
     * @param string $rule
     * @return array
     */
    protected function getByRule($rule)
    {
        return glob($this->getOwner()->getPath() . DIRECTORY_SEPARATOR . $rule);
    }
    
    /**
     * Checks if class that located in the file has needed parents.
     * @param string $file absolute path.
     * @return bool
     */
    protected function checkParent($file)
    {
        $parser = new AmClassInfo($file);
        
        if ($include = $this->baseClass) {
            if (!$parser->isSubclassOf($include)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Map ClassInfo methods to make them accessible for entities as properties.
     */
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getClassInfo()->getDescription();
    }
    
    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->getClassInfo()->getSummary();
    }
    
    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->getClassInfo()->getAuthor();
    }
    
    /**
     * @return string
     */
    public function getLink()
    {
        return $this->getClassInfo()->getLink();
    }
    
    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->getClassInfo()->getVersion();
    }
    
    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getClassInfo()->getName();
    }
}