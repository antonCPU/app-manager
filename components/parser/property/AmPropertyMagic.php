<?php
Yii::import('appManager.components.parser.property.AmProperty');
require_once 'Zend/Reflection/Docblock/Tag/Param.php';

/**
 * Handles properties that have setters.
 */
class AmPropertyMagic extends AmProperty
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
                $content = array();
                if ($long = $doc->getLongDescription()) {
                    $content[] = $long;
                }
                if ($doc->hasTag('param')) {
                    $matches = array();
                    preg_match('#(@param.*?)(\n)(?:@|\r?\n|$)#s', $doc->getContents(), $matches);
                    $pattern = '/@param \b[\w\|]+\b(\s+)\$[\w]+\b/';
                    if ($param = $this->parseDescription($pattern, $matches[1])) {
                        $content[] = $param;
                    }
                }
                if (!$content && $short = $doc->getShortDescription()) {
                    $content[] = $short;
                }
                if ($content) {
                    $desc = implode("\n", $content);
                }
            }
        } catch (Exception $e) {};
        
        return $desc;
    }
}
