<?php
require_once 'Zend/Reflection/Docblock/Tag/Param.php';
Yii::import('appManager.components.parser.property.AmClassProperty');

/**
 * Handles properties that have setters.
 */
class AmPropertyMagic extends AmClassProperty
{
    public function __construct(Zend_Reflection_Class $class, Zend_Reflection_Method $reflector)
    {
        parent::__construct($class, $reflector);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        $methodName = $this->getReflector()->getName();
        $name    = str_replace('set', '', $methodName);
        $name[0] = strtolower($name[0]);
        return $name;
    }
    
    /**
     * @return array
     */
    public function getTypes()
    {
        $type = null;
        $parameters = $this->getReflector()->getParameters();
        if (!empty($parameters[0])) {
            try {
                $type = $parameters[0]->getType();
            } catch (Exception $e) {};
        }
        return $this->parseTypes($type);
    }
    
    /**
     * @return string 
     */
    public function getDescription()
    {
        $desc = null;
        try {
            if ($doc = $this->getReflector()->getDocblock()) {
                $pattern = '/@param \b[\w\|]+\b \$[\w]+\b/';
                $desc = $this->parseDescription($pattern, $doc->getContents());
            }
        } catch (Exception $e) {};
        
        return $desc;
    }
}
