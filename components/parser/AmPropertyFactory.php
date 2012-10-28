<?php
/**
 * Creates properties.
 */
class AmPropertyFactory
{
    /**
     * Creates public property.
     * @param Zend_Reflection_Class $class
     * @param Zend_Reflection $property
     * @return AmProperty
     */
    public static function create($class, $property)
    {
        return new AmProperty($class, $property);
    }
    
    /**
     * Creates property from its setter.
     * @param Zend_Reflection_Class $class
     * @param string                $method
     * @return AmProperty|false false if the method is not setter.
     */
    public static function createFromMethod($class, $method)
    {
         if ((0 === strpos($method->name, 'set')) && (strlen($method->name) > 3)) {
            if (1 == count($method->getParameters())) {
                $name    = preg_replace('/^set/', '', $method->name);
                $name[0] = strtolower($name[0]);
                if ($class->hasProperty($name)) {
                    return new AmPropertyMagic($class, $class->getProperty($name));
                } else {
                    return new AmPropertyVirtual($class, $name);
                }
            }
         }
         return false;
    }
}