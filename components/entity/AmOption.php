<?php
/**
 * Represents an entity option.
 */
class AmOption extends AmModel
{
    /**
     * @var string 
     */
    protected $name;
    /**
     * @var mixed 
     */
    protected $value;
    /**
     * @var mixed initial value. 
     */
    protected $default;
    /**
     * @var string 
     */
    protected $description;
    /**
     * @var string one of acceptable by phpdoc types.
     */
    protected $type;
    /**
     * @var string the value that could be used for displaying. 
     */
    protected $textValue;
    
    /**
     * @param string $name 
     */
    public function setName($name)
    { 
        $this->name = $name;
    }
    
    /**
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param mixed $value 
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * @return mixed 
     */
    public function getValue()
    {
        if (null === $this->value) {
            return $this->getDefault();
        }
        return $this->value;
    }
    
    /**
     * Determines if the current value is equal to the default.
     * @return bool 
     */
    public function isDefault()
    {
        return ($this->getValue() === $this->getDefault());
    }
    
    /**
     * @param mixed $value 
     */
    public function setDefault($value)
    {
        $this->default = $value;
    }
    
    /**
     * @return mixed 
     */
    public function getDefault()
    {
        return $this->default;
    }
    
    /**
     * Gets value that could be displayed correctly.
     * @return string 
     */
    public function getTextValue()
    {
        if (null === $this->textValue) {
            $this->textValue = $this->formTextValue($this->getValue());
        }
        return $this->textValue;
    }
    
    /**
     * @param string $value 
     */
    public function setTextValue($value)
    {
        $this->textValue = $value;
    }
    
    /**
     * @return string 
     */
    public function getTextDefault()
    {
        return $this->formTextValue($this->getDefault());
    }
    
    /**
     * @param string $desc 
     */
    public function setDescription($desc)
    {
        $this->description = $desc;
    }
    
    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->description;
    }
    
    /**
     * @param string $type 
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Determines if the value can contain array data.
     * @return bool 
     */
    public function getIsArray()
    {
        return (false !== strpos($this->getType(), 'array'));
    }
    
    /**
     * Populates the value using text value.
     */
    public function processTextValue()
    {   
        $this->value = $this->evaluateExpression($this->getTextValue());
    }
    
    /**
     * @return array
     * @see CModel::attributeLabels()
     */
    public function attributeLabels()
    {
        return array(
            'description' => 'Description',
        );
    }
    
    /**
     * Generates text value.
     * @param mixed $value
     * @param int   $level used internaly only during recursion call.
     * @return string 
     */
    protected function formTextValue($value, $level = 0)
    {
        if ($value instanceof CMap) {
            $value = $value->toArray();
        }
        if (!is_array($value)) {
            return CVarDumper::dumpAsString($value);
        } elseif (empty($value)) {
            $result = 'array()';
        } else {
            $result = '';
            $keys   = array_keys($value);
            $spaces = str_repeat(' ', $level*4);
            $result.= "array\n" . $spaces . '(';
            foreach($keys as $key)
            {
                $key2 = str_replace("'", "\\'", $key);
                $result.= "\n" . $spaces . '    '; 
                if (!is_numeric($key2)) {
                    $result.= "'$key2' => ";
                } 
                $result.= $this->formTextValue($value[$key], $level+1) . ',';
            }
            $result.= "\n" . $spaces . ')';
        }
        return $result;
    }
}
