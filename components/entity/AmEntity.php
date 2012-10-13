<?php

class AmEntity extends AmModel
{
    protected $id;
    protected $title;
    protected $options;
    protected $attributes;
    
    private $_path;
    private $_fileName;
    private $_parser;
    private $_config;
    
    /**
     * Initialization.
     * @param string $path full path or with aliases.
     */
    public function __construct($path = null)
    {
        $this->setPath($path);
    }
    
    public function __get($name)
    {
        if(isset($this->attributes[$name])) {
			return $this->attributes[$name];
        } elseif (isset($this->getParser()->$name)) {
            return $this->attributes[$name] = $this->getParser()->$name;
        }
        return parent::__get($name);
    }
    
    /**
     * @return string 
     */
    public function getId()
    {
        if (null === $this->id) {
            $this->id = $this->createId();
        }
        return $this->id;
    }
    
    /**
     * Forms unique identifier.
     * @return string
     */
    protected function createId()
    {
        $name = $this->getName();
        $name[0] = strtolower($name[0]);
        return (string)$name;
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
    
    /**
     * @param string $path full path with directory separators or with aliases. 
     */
    public function setPath($path)
    {
        if (!empty($path)) {
            $this->_path = $this->resolveFullPath($path);
        }
    }
    
    /**
     * @return string 
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * @return string 
     */
    public function getFileName()
    {
        if (null === $this->_fileName) {
            $this->_fileName = $this->resolveFileName($this->getPath());
        }
        return $this->_fileName;
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
     * Finds full path using supplied.
     * @param string $path could be with Yii aliases
     * @return string 
     */
    protected function resolveFullPath($path)
    {            
        if ($fullPath = Yii::getPathOfAlias($path)) {
            if (is_file($fullPath . '.php')) {
                $fullPath.= '.php';
            }
        } else {
            $fullPath = $path;
        }
        return $fullPath;
    }

    /**
     * Finds full path to entity file.
     * @param string $path
     * @return string 
     * @throws CException in case if file does not exist
     */
    protected function resolveFileName($path)
    {  
        if (false === strpos($path, '.')) {
            $path .= DIRECTORY_SEPARATOR . $this->formFileName(basename($path));
        }
        if (!file_exists($path)) {
            throw new CException(AppManagerModule::t('{path} does not exist.', 
                                 array('{path}' => $path)));
        }
        return $path;
    }
     
    /**
     * Forms class name.
     * @return string 
     */
    protected function resolveClassName()
    {
        $name = $this->resolveName();
        $tmp = explode('.', $name);
        return array_pop($tmp);
    }
    
    /**
     * Forms file name.
     * @param string $name
     * @return string 
     */
    protected function formFileName($name)
    {
        return $name . '.php';
    }
   
    public function getComponents()
    {
        return null;
    }
    
    public function getModules()
    {
        return null;
    }
    
    public function getExtension()
    {
        return null;
    }
}