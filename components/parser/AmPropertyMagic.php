<?php
/**
 * Handles properties that has setters.
 */
class AmPropertyMagic extends AmProperty
{
    /**
     * @return string 
     */
    public function getType()
    {
        $type = parent::getType();
        if (null === $type) {
            $type = $this->parseMethodType();
        }
        return $type;
    }
    
    /**
     * Gets type from the method parameters.
     * @return string 
     */
    protected function parseMethodType()
    {
        $type = null;
        $parameters = $this->getMethod()->getParameters();
        if (!empty($parameters[0])) {
            try {
                $type = $parameters[0]->getType();
            } catch (Exception $e) {};
        }
        return $type;
    }
    
    /**
     * @return string 
     */
    public function getDescription()
    {
        $desc = parent::getDescription();
        if (null === $desc) {
            $desc = $this->parseMethodDescription();
        }
        return $desc;
    }
    
    /**
     * Gets description from the method parameters.
     * @return string 
     */
    protected function parseMethodDescription()
    {
        $desc = null;
        try {
            if ($doc = $this->getMethod()->getDocblock()) {
                $pattern = '/@param \b[\w\|]+\b \$[\w]+\b/';
                $desc = $this->parseDescription($pattern, $doc->getContents());
            }
        } catch (Exception $e) {};
        
        return $desc;
    }
    
    /**
     * @return string 
     */
    protected function getMethodName()
    {
        return  'set' . ucfirst($this->getName());
    }
    
    /**
     * @return Zend_Reflection_Method 
     */
    protected function getMethod()
    {
        require_once 'Zend/Reflection/Docblock/Tag/Param.php';
        return $this->getClass()->getMethod($this->getMethodName());
    }
}
