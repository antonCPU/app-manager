<?php

class AmClassBehavior extends CBehavior
{
    public $searchPatterns = array('*.php');
    public $baseClass      = 'CComponent';
    
    protected $classInfo;
    protected $fileName;
    protected $path;
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
     * @return string
     */
    public function getClassName()
    {
        return $this->getClassInfo()->getName();
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
     * Gets an absolute path to the source (file or directory).
     * @return string 
     */
    public function getPath()
    {
        if (null === $this->path) {
            $this->path = Yii::getPathOfAlias($this->getOwner()->getId());
        }
        return $this->path;
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
     * Finds related to entity class.
     * @param AmEntity $entity
     * @return string|bool full alias of class or false.
     */
    public function resolveFullClassName()
    {
        $id = $this->getOwner()->getId();
        $file = $this->getPath() . '.php';
        if (!is_file($file)) {
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
        return glob($this->getPath() . DIRECTORY_SEPARATOR . $rule);
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
    
}