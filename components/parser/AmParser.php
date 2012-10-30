<?php
/**
 * Gets information about class details.
 * Facade to Zend_Reflection library.
 */
class AmParser extends CComponent
{
    /**
     * @var string full description.
     */
    protected $description;
    /**
     * @var string short description.
     */
    protected $summary;
    /**
     * @var string author's details.
     */
    protected $author;
    /**
     * @var string entity web reference.
     */
    protected $link;
    /**
     * @var string 
     */
    protected $version;
    /**
     * @var string full path to file.
     */
    protected $fileName;
    /**
     * @var string current class. 
     */
    protected $className;
    
    /**
     * @var Zend_Reflection_File
     */
    private $_file;
    /**
     * @var Zend_Reflection_Docblock
     */
    private $_fileDoc;
    /**
     * @var Zend_Reflection_Class 
     */
    private $_class;
    /**
     * @var Zend_Reflection_Docblock 
     */
    private $_classDoc;
    
    /**
     * @param string $fileName 
     * @see AppManagerParser::$fileName
     */
    public function __construct($fileName)
    {
        $this->setFileName($fileName);
    }
    
    /**
     * @param string $fileName
     * @return AppManagerParser 
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * @return string 
     */
    public function getClassName()
    {
        if (null === $this->className) {
            $this->className = basename($this->getFileName(), '.php');
        }
        return $this->className;
    }
    
    /**
     * @return string 
     */
    public function getDescription()
    { 
        if ((null === $this->description) && $doc = $this->getClassDoc()) {
            $this->description = $doc->getLongDescription();
        }
        return $this->description;
    }
    
    /**
     * @return string 
     */
    public function getSummary()
    {
        if ((null === $this->summary) && $doc = $this->getClassDoc()) {
            $this->summary = $doc->getShortDescription();
        }
        return $this->summary;
    }
    
    /**
     * @return string 
     */
    public function getAuthor()
    {
        if ((null === $this->author) && $tag = $this->getTag('author')) {
            $this->author = $tag->getDescription();
        }
        return $this->author;
    }
    
    /**
     * @return string 
     */
    public function getLink()
    { 
        if ((null === $this->link) && $tag = $this->getTag('link')) {
            $this->link = $tag->getDescription();
        } 
        return $this->link;
    }
    
    /**
     * @return string
     */
    public function getVersion()
    {
        if ((null === $this->version) && $tag = $this->getTag('version')) {
            $this->version = $tag->getDescription();
        } 
        return $this->version;
    }
    
    /**
     * @param string $class
     * @return bool 
     */
    public function isSubclassOf($class) 
    {
        $result = false;
        try {
            $result = $this->getClass()->isSubclassOf($class);
        } catch (Exception $e) {}
        
        return $result;
    }
    
    /**
     * Gets public class properties.
     * Also non public properties if they have related "set" magic method.
     * @return AppManagerProperty 
     */
    public function getProperties()
    {
        $result = array_merge(
                $this->parsePublicProperties(), 
                $this->parseMagicProperties()
        ); 
        return $result;
    }

    /**
     * Searches for public properties.
     * @return AmProperty[]
     */
    protected function parsePublicProperties()
    {
        $result = array();
        $class = $this->getClass();
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $result[] = AmPropertyFactory::create($class, $property);
        }
        return $result;
    }
    
    /**
     * Searches for non public properties that has setters. 
     * @return AmProperty[]
     */
    protected function parseMagicProperties()
    {
        $result = array();
        $class = $this->getClass();
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($property = AmPropertyFactory::createFromMethod($class, $method)) {
                $result[] = $property;
            }
        }
        return $result;
    }
    
    /**
     * @return Zend_Reflection_File 
     */
    protected function getFile()
    {
        if (null === $this->_file) {
            $this->_file = $this->loadFile();
        }
        return $this->_file;
    }
    
    /**
     * @return Zend_Reflection_File 
     */
    protected function loadFile()
    {
        Yii::import('appManager.vendors.*');
        require_once 'Zend/Reflection/File.php';
        require_once $this->getFileName();
        return new Zend_Reflection_File($this->getFileName());
    }
    
    /**
     * @return Zend_Reflection_Docblock
     */
    protected function getFileDoc()
    {
        if (null === $this->_fileDoc) {
            $doc = null;
            try {
                $doc = $this->getFile()->getDocblock();
            } catch (Exception $e) {}
            $this->_fileDoc = $doc;
        } 
        return $this->_fileDoc;
    }
    
    /**
     * @return Zend_Reflection_Class 
     */
    protected function getClass()
    {
        if (null === $this->_class) {
            $this->_class = $this->loadClass();
        }
        return $this->_class;
    }
    
    /**
     * @return Zend_Reflection_Class 
     */
    protected function loadClass()
    {
        $classes = $this->getFile()->getClasses();
        return isset($classes[0]) ? $classes[0] : null;
    }
    
    /**
     * @return Zend_Reflection_Docblock 
     */
    protected function getClassDoc()
    {
        if (null === $this->_classDoc) {
            $doc = false;
            try {
                $doc = $this->getClass()->getDocblock();
            } catch (Exception $e) {}
            $this->_classDoc = $doc;
        } 
        return $this->_classDoc;
    }
    
    /**
     * Gets documentation for the specific tag name.
     * @param string $name
     * @return string|null 
     */
    protected function getTag($name)
    {
        if (($doc = $this->getClassDoc()) && $tag = $doc->getTag($name)) {
            return $tag;
        } elseif (($doc = $this->getFileDoc()) && $tag = $doc->getTag($name)) {
            return $tag;
        }
        return null;
    }
}