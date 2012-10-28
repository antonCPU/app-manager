<?php
/**
 * Handles properties that are not class attributes, but has setters.
 */
class AmVirtualProperty extends AmMagicProperty
{
    /**
     * @return string 
     */
    public function getName()
    {
        return $this->getProperty();
    }
    
    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }
    
    /**
     * @return string 
     */
    public function getType()
    {
        return $this->parseMethodType();
    }
    
    /**
     * @return string 
     */
    public function getDescription()
    {
        return $this->parseMethodDescription();
    }
}