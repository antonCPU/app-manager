<?php
/**
 * Options collection.
 * Helper for dealing with entity options.
 */
class AmOptions extends AmModel
{
    /**
     * @var AmOption[]. 
     */
    protected $options;
    /**
     * @var AmProperty[] 
     */
    protected $properties;
    /**
     * @var AmNode 
     */
    protected $config;
    /**
     * @var array list of options that should be excluded
     */
    protected $exclude = array();
    
    /**
     * @param AmProperty[] $properties
     * @param AmNode       $config
     */
    public function __construct($properties, $config)
    {
        $this->properties = $properties;
        $this->config     = $config;
    }
    
    /**
     * Validation rules.
     * @return array 
     */
    public function rules()
    {
        $attributes = implode(', ', array_keys($this->get()));
        return array(
            array($attributes, 'AmPhpValidator'),  
        );
    }
    
    /**
     * @return AmNode
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Organizes work with options like with not objects, but simple values.
     * @param string $name
     * @return mixed 
     */
    public function __get($name) 
    {
        $options = $this->get(); 
        if (isset($options[$name])) {
            $value = $options[$name]->getTextValue();
        } else {
            $value = parent::__get($name);
        }
        return $value;
    }
    
    /**
     * Populates options 'textValue'.
     * @param array $attributes 
     */
    public function setAttributes($attributes, $safeOnly = true)
    {
        $options = $this->get();
        foreach ((array)$attributes as $name => $value) {
            if (isset($options[$name])) {
                $options[$name]->setTextValue($value);
            }
        }
    }
    
    /**
     * @return AmOption[] 
     */
    public function get()
    {
        if (null === $this->options) {
            $this->options = $this->parseOptions();
        } 
        return $this->options;
    }
    
    /**
     * @return CArrayDataProvider 
     */
    public function getProvider()
    {
        return new CArrayDataProvider(array_values($this->get()), 
                array('keyField' => 'name'));
    }
    
    /**
     * @return CMapIterator 
     */
    public function getIterator()
    {
        $data = $this->get();
        return new CMapIterator($data);
    }
    
    /**
     * Gets options count.
     * @return int 
     */
    public function getCount()
    { 
        return count($this->get());
    }
    
    /**
     * @param string $attribute
     * @return string 
     */
    public function getAttributeLabel($attribute)
    {
        return $attribute;
    }
    
    /**
     * Updates config instance with new options.
     * Performs validation first.
     * @return bool 
     */
    public function updateConfig()
    {
        if (!$this->validate()) {
            return false;
        }
        $config = $this->getConfig();
        foreach ($this->get() as $option) {
            if ($option->isDefault()) {
                $config->remove($option->getName());
            } else {
                $config->add($option->getName(), $option->getValue());
            }
        }
        return true;
    }
    
    /**
     * Generates options list.
     * @return array 
     */
    protected function parseOptions()
    {
        $options = array();
        $exclude = $this->exclude;
        foreach ($this->properties as $property) {
            if (in_array($property->name, $exclude)) {
                continue;
            }
            $option = new AmOption($property);
            $value = $this->getConfigValue($property->name);
            if ($value instanceof AmNode) {
                $value = $value->toArray();
            }
            $option->setValue($value);
            $options[$property->name] = $option;
        }
        return $options;
    }
    
    /**
     * Provides value from the config.
     * @param string $name
     * @return mixed 
     */
    protected function getConfigValue($name)
    {
        if ($config = $this->getConfig()) {
            return $config->itemAt($name);
        }
        return null;
    }
}
