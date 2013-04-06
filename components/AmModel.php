<?php
/**
 * Implements specific for AppManager model behaviour.
 */
abstract class AmModel extends CModel
{
    private static $_names=array();

    /**
     * Sets the attribute values in a massive way.
     * @param array $attributes attribute values (name=>value) to be set.
     * @param bool $safeOnly is not used.
     */
    public function setAttributes($attributes, $safeOnly = true)
    {
        foreach ((array)$attributes as $name => $value) {
            $this->$name = $value;
        }
    }
    
    /**
     * Initializes this model.
     * This method is invoked in the constructor right after {@link scenario} is set.
     * You may override this method to provide code that is needed to initialize the model (e.g. setting
     * initial property values.)
     */
    public function init() {}

    /**
     * Returns the list of attribute names.
     * By default, this method returns all public properties of the class.
     * You may override this method to change the default.
     * @return array list of attribute names. Defaults to all public properties of the class.
     */
    public function attributeNames()
    {
        $className=get_class($this);
        if(!isset(self::$_names[$className])) {
            $class=new ReflectionClass(get_class($this));
            $names=array();
            foreach($class->getProperties() as $property) {
                $name=$property->getName();
                if($property->isPublic() && !$property->isStatic()) {
                    $names[]=$name;
                }
            }
            return self::$_names[$className]=$names;
        } else {
            return self::$_names[$className];
        }
    }
}