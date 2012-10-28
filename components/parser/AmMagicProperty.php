<?php

class AmMagicProperty extends AmProperty
{
    public function getType()
    {
        $type = parent::getType();
        if (null === $type) {
            $type = $this->parseMethodType();
        }
        return $type;
    }
    
    protected function parseMethodType()
    {
        $type = null;
        $parameters = $this->getMethod()->getParameters();
        if (!empty($parameters[0])) {
            $type = $parameters[0]->getType();
        }
        return $type;
    }
    
    public function getDescription()
    {
        $desc = parent::getDescription();
        if (null === $desc) {
            $desc = $this->parseMethodDescription($pattern, $doc->getContents());
        }
        return $desc;
    }
    
    protected function parseMethodDescription()
    {
        $desc = null;
        if ($doc = $this->getMethod()->getDocblock()) {
            $pattern = '/@param \b[\w\|]+\b \$[\w]+\b/';
            $desc = $this->parseDescription($pattern, $doc->getContents());
        }
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
