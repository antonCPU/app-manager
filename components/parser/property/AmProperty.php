<?php
/**
 * Adapter that represents any class content as property.
 */
abstract class AmProperty extends CComponent
{
    const TYPE_STRING   = 'string';
    const TYPE_INTEGER  = 'integer';
    const TYPE_BOOLEAN  = 'boolean';
    const TYPE_FLOAT    = 'float';
    const TYPE_ARRAY    = 'array';
    const TYPE_OBJECT   = 'object';
    const TYPE_MIXED    = 'mixed';
    const TYPE_RESOURCE = 'resource';
    
    /**
     * @var ReflectionClass 
     */
    protected $class;
    /**
     * @var mixed 
     */
    protected $reflector;
    
    /**
     * @param ReflectionClass $class
     * @param mixed           $reflector
     */
    public function __construct(ReflectionClass $class, $reflector)
    {
        $this->class     = $class;
        $this->reflector = $reflector;
    }
    
    /**
     * @return string|null
     */
    public function getName()
    {
        return null;
    }
    
    /**
     * @return mixed
     */
    public function getValue()
    {
        return null;
    }
    
    /**
     * @return string|null
     */
    public function getType()
    {
        if ($types = $this->getTypes()) {
            if (count($types) > 1) {
                return self::TYPE_MIXED;
            }
            return $types[0];
        }
        return null;
    }
    
    /**
     * @return array
     */
    public function getTypes()
    {
        return array();
    }
    
    /**
     * @return string|null
     */
    public function getDescription()
    {
        return null;
    }
    
    /**
     * @return array
     */
    public static function getAllTypes()
    {
        return array(
            self::TYPE_STRING,
            self::TYPE_INTEGER,
            self::TYPE_BOOLEAN,
            self::TYPE_FLOAT,
            self::TYPE_ARRAY,
            self::TYPE_OBJECT,
            self::TYPE_MIXED,
            self::TYPE_RESOURCE,
        );
    }
    
    /**
     * @return mixed
     */
    protected function getReflector()
    {
        return $this->reflector;
    }
    
    /**
     * @return ReflectionClass
     */
    protected function getClass()
    {
        return $this->class;
    }
    
    /**
     * @param string $typeLine
     * @return array
     */
    protected function parseTypes($typeLine)
    {
        if (empty($typeLine)) {
            return array();
        }
        $types = array();
        foreach (explode('|', $typeLine) as $type) {
            $types[] = $this->parseType($type);
        }
        return $types;
    }
    
    /**
     * @param string $content
     */
    protected function parseType($content)
    {
        if (!$content) {
            return null;
        }
        $content = strtolower($content);
        foreach (self::getAllTypes() as $type) {
            if (0 === strpos($type, $content)) {
                return $type;
            }
        }
        return null;
    }
    
    /**
     * Filters description.
     * @param string $pattern regexp.
     * @param string $content
     * @return string
     */
    protected function parseDescription($pattern, $content)
    {
        return ucfirst(trim(preg_replace($pattern, '', $content)));
    }
    
}
