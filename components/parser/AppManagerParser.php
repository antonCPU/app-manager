<?php
/**
 * Gets information about class details.
 * Facade to Zend_Reflection library.
 */
class AppManagerParser extends CComponent
{
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
    public function getDesc()
    { 
        if ($doc = $this->getClassDoc()) {
            return $doc->getLongDescription();
        }
        return null;
    }
    
    /**
     * @return string 
     */
    public function getShortDesc()
    {
        if ($doc = $this->getClassDoc()) {
            return $doc->getShortDescription();
        }
        return null;
    }
    
    /**
     * @return string 
     */
    public function getAuthor()
    {
        if ($tag = $this->getTag('author')) {
            return $tag->getDescription();
        }
        return null;
    }
    
    /**
     * @return string 
     */
    public function getLink()
    { 
        if ($tag = $this->getTag('link')) {
            return $tag->getDescription();
        }
        return null;
    }
    
    /**
     * @param string $class
     * @return bool 
     */
    public function isSubclassOf($class) 
    {
        return $this->getClass()->isSubclassOf($class);
    }
    
    /**
     * Gets public class properties.
     * Also non public properties if they have related "set" magic method.
     * @return AppManagerProperty 
     */
    public function getProperties()
    {
        $result = array();
        foreach ($this->getClass()->getProperties() as $property) {
            $property = new AppManagerProperty($property);
            if ($property->isPublic()) {
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