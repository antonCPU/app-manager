<?php
/**
 * Works with class properties.
 */
class AmProperty extends CComponent
{    
    /**
     * @var Zend_Reflection_Class 
     */
    protected $class;
    /**
     * @var Zend_Reflection_Property 
     */
    protected $property;
    
    /**
     * @param Zend_Reflection_Property $property 
     */
    public function __construct($class, $property)
    {
        require_once 'Zend/Reflection/Docblock/Tag/Return.php';
        $this->class    = $class;
        $this->property = $property;
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
        $name     = $this->getName();
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
                if ($filtered = substr($type, 0, strpos($type, ' '))) {
                    $type = $filtered;
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
    
    /**
     * Filters description.
     * @param string $pattern regexp.
     * @param string $content
     * @return string
     */
    protected function parseDescription($pattern, $content)
    {
        return ucfirst(trim(preg_replace($pattern, '', $content)));
    }
    
    /**
     * @return Zend_Reflection_Class 
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * @return Zend_Reflection_Property 
     */
    public function getProperty()
    {
        return $this->property;
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
