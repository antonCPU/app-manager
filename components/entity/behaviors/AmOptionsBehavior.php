<?php
/**
 * @uses AmEntity::getProperties()
 * @uses AmEntity::getConfig()
 */
class AmOptionsBehavior extends CBehavior
{
    /**
     * @var AmOptions 
     */
    protected $options;
    
    /**
     * Gets attributes for editing.
     * @return AmOptions 
     */
    public function getOptions() 
    { 
        if (null === $this->options) {
            $properties = $this->getOwner()->getProperties();
            $this->options = new AmOptions($properties, $this->getOwner()->getConfig()); 
        }
        return $this->options;
    }
    
    /**
     * Sets options from input data.
     * @param array $options 
     */
    public function setOptions($options)
    { 
        $model = $this->getOptions();
        $model->setAttributes($options);
        return $model->updateConfig();
    }
}