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
    public function __construct($class, $property)
    {
        require_once 'Zend/Reflection/Docblock/Tag/Return.php';
        $this->setClass($class)->setProperty($property);
    }
    
    public function setClass($class)
    {
        $this->_class = $class;
        return $this;
    }

    /**
     * @return string 
     */
    public function getName()
    {
        return $this->getProperty()->getName();
    }
    
    /**
     * @return string 
     */
    public function getValue()
    {
        $name = $this->getName();
        $defaults = $this->getDefaults();
        return isset($defaults[$name]) ? $defaults[$name] : null;
    }
    
    /**
     * @return string 
     */
    public function getType()
    {
        $type = null;
        if ($doc = $this->getProperty()->getDocComment()) { 
            if (($tag = $doc->getTag('var')) 
                || ($tag = $doc->getTag('property'))
            ) {
                $type = $tag->getDescription();
                if ($filtered = strstr($type, ' ', true)) {
                    return $filtered;
                }
            }
        } 
        return $type;
    }
    
    /**
     * @return string 
     */
    public function getDescription()
    {
        $desc = null;
        if ($doc = $this->getProperty()->getDocComment()) {
            $pattern = '/(@var \b[\w\|]+\b)|(@property \b[\w\|]+\b)/';
            $desc = $this->parseDescription($pattern, $doc->getContents());
        } 
        return $desc;
    }
    
    protected function parseDescription($pattern, $content)
    {
        return ucfirst(trim(preg_replace($pattern, '', $content)));
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
     * Gets values for properties.
     * @return array 
     */
    protected function getDefaults()
    {
        return $this->getClass()->getDefaultProperties();
    }
}
