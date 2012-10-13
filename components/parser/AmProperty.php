<?php
/**
 * Works with class properties.
 */
class AmProperty extends CComponent
{    
    /**
     * @var Zend_Reflection_Class 
     */
    private $_class;
    /**
     * @var Zend_Reflection_Property 
     */
    private $_property;
    /**
     * @var bool if property has magic set method. 
     */
    private $_isMagic;
    
    /**
     * @param Zend_Reflection_Property $property 
     */
    public function __construct($property)
    {
        require_once 'Zend/Reflection/Docblock/Tag/Return.php';
        $this->setProperty($property);
    }
    
    /**
     * @return bool 
     */
    public function isPublic()
    {
        return $this->getProperty()->isPublic() || $this->isMagic();
    }
    
    /**
     * @return string 
     */
    public function getName()
    {
        return preg_replace('/^_/', '', $this->getProperty()->getName());
    }
    
    /**
     * @return string 
     */
    public function getValue()
    {
        $name = $this->getProperty()->getName();
        $defaults = $this->getDefaults();
        return isset($defaults[$name]) ? $defaults[$name] : null;
    }
    
    /**
     * @return string 
     */
    public function getType()
    {
        if ($doc = $this->getProperty()->getDocComment()) { 
            if (($tag = $doc->getTag('var')) 
                || ($tag = $doc->getTag('property'))
            ) {
                $type = $tag->getDescription();
                if ($filtered = strstr($type, ' ', true)) {
                    return $filtered;
                }
                return $type;
            }
        } elseif ($this->isMagic()) {
            $parameters = $this->getMagicMethod()->getParameters();
            if (!empty($parameters[0])) {
                return $parameters[0]->getType();
            }
        }
        
        return null;
    }
    
    /**
     * @return string 
     */
    public function getDescription()
    {
        if ($doc = $this->getProperty()->getDocComment()) {
            $pattern = '/(@var \b[\w\|]+\b)|(@property \b[\w\|]+\b)/';
        } elseif ($this->isMagic()) {
            $doc = $this->getMagicMethod()->getDocblock();
            $pattern = '/@param \b[\w\|]+\b \$[\w]+\b/';
        }
        
        if (!$doc) {
            return null;
        }
        return ucfirst(trim(preg_replace($pattern, '', $doc->getContents())));
    }
    
    /**
     * @return Zend_Reflection_Class 
     */
    public function getClass()
    {
        if (null === $this->_class) {
            $this->_class = $this->getProperty()->getDeclaringClass();
        } 
        return $this->_class;
    }
    
    /**
     * @param Zend_Reflection_Property $property 
     */
    public function setProperty($property)
    {
        $this->_property = $property;
    }
    
    /**
     * @return Zend_Reflection_Property 
     */
    public function getProperty()
    {
        return $this->_property;
    }
    
    /**
     * @return bool 
     */
    public function isMagic()
    {
        if (null === $this->_isMagic) {
            $this->_isMagic = $this->getClass()
                                   ->hasMethod($this->getMagicMethodName());
        }
        return $this->_isMagic; 
    }
    
    /**
     * Gets values for properties.
     * @return array 
     */
    protected function getDefaults()
    {
        return $this->getClass()->getDefaultProperties();
    }
    
    /**
     * @return string 
     */
    protected function getMagicMethodName()
    {
        return  $name = 'set' . ucfirst($this->getName());
    }
    
    /**
     * @return Zend_Reflection_Method 
     */
    protected function getMagicMethod()
    {
        require_once 'Zend/Reflection/Docblock/Tag/Param.php';
        return $this->getClass()->getMethod($this->getMagicMethodName());
    }
}
