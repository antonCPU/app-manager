<?php
/**
 * Handles properties that are not class attributes, but has setters.
 */
class AmPropertyVirtual extends AmPropertyMagic
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