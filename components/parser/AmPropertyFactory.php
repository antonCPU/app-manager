<?php

class AmPropertyFactory
{
    public static function create($class, $property)
    {
        if (is_string($property)) {
            if ($class->hasProperty($property)) {
                return new AmMagicProperty($class, $property);
            } else {
                return new AmVirtualProperty($class, $property);
            }
        } 
        return new AmProperty($class, $property);
    }
    
    public static function createFromMethod($class, $method)
    {
         if (0 === strpos($method->name, 'set')) {
            if (1 == count($method->getParameters())) {
                $name = lcfirst(str_replace('set', '', $method->name));
                return self::create($class, $name);
            }
         }
         return false;
    }
}