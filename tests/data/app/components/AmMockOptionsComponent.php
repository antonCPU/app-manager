<?php

class AmMockOptionsComponent extends CComponent
{
    public $propertyText = 'text';
    public $propertyInt = 123;
    
    protected $property;
    
    public function propertySetter($value)
    {
        $this->property = $value;
    }
    
    public function propertyGetter()
    {
        return $this->property;
    }
}