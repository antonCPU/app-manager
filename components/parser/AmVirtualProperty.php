<?php

class AmVirtualProperty extends AmMagicProperty
{
    public function getName()
    {
        return $this->getProperty();
    }
    
    public function getType()
    {
        return $this->parseMethodType();
    }
    
    public function getDescription()
    {
        return $this->parseMethodDescription();
    }
}