<?php
Yii::import('appManager.components.parser.property.AmProperty');
require_once 'Zend/Reflection/Docblock/Tag/Return.php';

/**
 * Handles own class properties.
 */
class AmPropertyOwn extends AmProperty
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
            $content = array();
            if ($short = $doc->getShortDescription()) {
                $content[] = rtrim($short, '.') . '.';
            }
            $content[] = $doc->getLongDescription();
           
            if ($doc->hasTag('var') || $doc->hasTag('property')) {
                $matches = array();
                $pattern = '#(@var|@property)(\s+)(\b[\w\|]+\b)(.*?)(\n)(?:@|\r?\n|$)#s';
                preg_match($pattern, $doc->getContents(), $matches);
                if (isset($matches[4])) {
                    $content[] = ucfirst(trim($matches[4]));
                }
            }
            $desc = implode("\n", array_filter($content));
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
