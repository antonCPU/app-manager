<?php
Yii::import('appManager.components.parser.property.AmClassProperty');

/**
 * Handles own class properties.
 */
class AmPropertyOwn extends AmClassProperty
{
    public function __construct(Zend_Reflection_Class $class, Zend_Reflection_Property $reflector)
    {
        parent::__construct($class, $reflector);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getReflector()->getName();
    }
    
    /**
     * @return mixed
     */
    public function getValue()
    {
        $name     = $this->getName();
        $defaults = $this->getDefaults();
        return isset($defaults[$name]) ? $defaults[$name] : null;
    }
    
    /**
     * @return array
     */
    public function getTypes()
    {
        $type = null;
        if ($doc = $this->getReflector()->getDocComment()) { 
            if (($tag = $doc->getTag('var')) 
                || ($tag = $doc->getTag('property'))
            ) {
                $type = $tag->getDescription();
                if ($filtered = substr($type, 0, strpos($type, ' '))) {
                    $type = $filtered;
                }
            }
        } 
        return $this->parseTypes($type);
    }
    
     /**
     * @return string 
     */
    public function getDescription()
    {
        $desc = null;
        if ($doc = $this->getReflector()->getDocComment()) {
            $pattern = '/(@var \b[\w\|]+\b)|(@property \b[\w\|]+\b)/';
            $desc = $this->parseDescription($pattern, $doc->getContents());
        } 
        return $desc;
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
