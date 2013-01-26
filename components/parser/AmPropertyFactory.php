<?php
Yii::import('appManager.components.parser.property.*');

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
        return new AmPropertyOwn($class, $property);
    }
    
    /**
     * Creates property from its setter.
     * @param Zend_Reflection_Class  $class
     * @param Zend_Reflection_Method $method
     * @return AmProperty|false false if the method is not setter.
     */
    public static function createFromMethod($class, $method)
    {
         if ((0 === strpos($method->name, 'set')) && (strlen($method->name) > 3)) {
            if (1 == count($method->getParameters())) {
                return new AmPropertyMagic($class, $method);
            }
         }
         return false;
    }
}