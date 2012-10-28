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
     * @var AmEntity
     */
    protected $entity;
    
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
     * @param AmEntity $entity
     * @return AmOptions
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    /**
     * @return AmEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * @return array 
     */
    public function get()
    {
        if (null === $this->options) {
            $this->options = $this->parseOptions();
        } 
        return $this->options;
    }
    
    /**
     * @param array $options
     */
    public function set($options)
    {
        $this->options = $options;
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
            $option->processTextValue();
            if ($option->isDefault()) {
                $config->remove($option->getName());
            } else {
                $config->add($option->getName(), $option->getValue());
            }
        }
        return true;
    }
    
    /**
     * @return AmParser
     */
    protected function getParser()
    {
        return $this->getEntity()->getParser();
    }
    
    /**
     * @return AmConfig
     */
    protected function getConfig()
    {
        return $this->getEntity()->getConfig();
    }
    
    /**
     * @return array
     */
    protected function getExclude()
    {
        return $this->getEntity()->getExcludeOptions();
    }
    
    /**
     * Generates options list.
     * @return array 
     */
    protected function parseOptions()
    {
        $properties = $this->getParser()->getProperties();
        $options = array();
        $exclude = $this->getExclude();
        foreach ($properties as $property) {
            if (in_array($property->name, $exclude)) {
                continue;
            }
            $option = new AmOption;
            $value = $this->getConfigValue($property->name);
            if ($value instanceof AmNode) {
                $value = $value->toArray();
            }
            $option->attributes = array(
                'name'    => $property->name,
                'default' => $property->value,
                'value'   => $value,
                'type'    => $property->type,
                'description' => $property->description,
            );
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
