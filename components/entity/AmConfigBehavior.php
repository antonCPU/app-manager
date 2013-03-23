<?php

class AmConfigBehavior extends CBehavior
{
    public $section;
    
    protected $config;
    protected $name;
    protected $defaultName;
    protected $options;
    
    /**
     * @return boolean
     */
    public function isActive()
    {
        return (bool)$this->getConfig()->count();
    }
    
    /**
     * @return boolean
     */
    public function canActivate()
    {
        return !$this->isActive();
    }
    
    /**
     * @return boolean
     */
    public function canDeactivate()
    {
        return $this->isActive();
    }
    
    /**
     * @return boolean
     */
    public function canUpdate()
    {
        return $this->isActive() && $this->isWritable();
    }
    
    /**
     * @return boolean
     */
    public function canRestore()
    {
        return $this->canUpdate() && $this->isChanged();
    }
    
    /**
     * Checks if config can be updated.
     * @return bool
     */
    public function isWritable()
    {
        return AppManagerModule::config()->isWritable();
    }
    
    /**
     * Verifies if the entity has data in the config.
     * @return bool
     */
    public function isEmpty()
    { 
        return !$this->getConfig()->count();
    }
    
    /**
     * Checks if the entity has overriden any options.
     * @return bool
     */
    public function isChanged()
    {
        return $this->getConfig()->count() > 1;
    }
    
    /**
     * Generates a name for the config file.
     * @return string
     */
    public function getDefaultName()
    {
        if (null === $this->defaultName) {
            $id = $this->getOwner()->getId();
            $tmp = explode('.', $id);
            $name = array_pop($tmp);
            $name[0] = strtolower($name[0]);
            $this->defaultName = $name;
        }
        return $this->defaultName;
    }
    
    /**
     * @param string $name
     * @return AmEntity
     */
    public function setDefaultName($name)
    {
        $this->defaultName = $name;
        return $this;
    }
    
    /**
     * Verifies if current name is a default.
     * @return bool
     */
    public function isDefaultName()
    {
        return ($this->getName() === $this->getDefaultName());
    }
    
    
    /**
     * @return string
     */
    public function getName()
    {
        if (null === $this->name) {
            $this->name = $this->resolveConfigName();
        }
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return AmConfigBehavior
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Adds entity to the config.
     * @return bool
     */
    public function activate()
    {
        if (!$this->canActivate()) {
            return false;
        }
        $this->loadSection()->add($this->getName(), array(
            'class' => $this->getOwner()->getFullClassName(),
        )); 
        return $this->save();
    }
    
    /**
     * Removes entity from the config.
     * @return bool
     */
    public function deactivate()
    {
        if (!$this->canDeactivate()) {
            return false;
        }
        $this->loadSection()->remove($this->getName());
        return $this->save();
    }
    
    /**
     * Restores entity options.
     * @return bool
     */
    public function restore()
    {
        if (!$this->canRestore()) {
            return false;
        }
        $config = $this->getConfig();
        $config->clear();
        $config->add('class', $this->getOwner()->getFullClassName());
        return $this->save();
    }
    
    /**
     * Updates entity data.
     * @return boolean
     */
    public function update()
    {
        if (!$this->canUpdate()) {
            return false;
        }
        $this->updateName();
        $this->getConfig()->add('class', $this->getOwner()->getFullClassName());
        if (!$this->getOptions()->updateConfig()) {
            return false;
        }
        return $this->save();
    }
    
    /**
     * Saves the config.
     * @return bool
     */
    protected function save()
    {
        if (AppManagerModule::config()->save()) {
            $this->config = null;
            return true;
        }
        return false;
    }
    
    /**
     * Changes entity name in the config.
     */
    protected function updateName()
    {
        $entity = $this->getOwner();
        if ($entity->getName() === $this->getName()) {
            return;
        }
        $this->loadSection()->remove($this->getName());
        $this->setName($entity->getName());
        $this->config = null;
    }
    
    /**
     * Gets config instance.
     * @return AmConfig
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->config = $this->load($this->getName());
        }
        return $this->config;
    }
    
    /**
     * Loads a config section related to the entity.
     * @return AmNode
     */
    public function loadSection()
    { 
        return AppManagerModule::config($this->section);
    }
    
    /**
     * Gets attributes for editing.
     * @return AmOptions 
     */
    public function getOptions() 
    { 
        if (null === $this->options) {
            $properties = $this->getOwner()->getProperties();
            $this->options = new AmOptions($properties, $this->getConfig()); 
        }
        return $this->options;
    }
    
    /**
     * Sets options from input data.
     * @param array $options 
     */
    public function setOptions($options)
    { 
        $this->getOptions()->attributes = $options;
    }
    
    /**
     * Loads data for the entity.
     * @param string $name
     * @param bool   $create = true
     * @return AmNode
     */
    protected function load($name, $create = true)
    {
        $config  = $this->loadSection();
        $this->normalizeConfig($config, $name);
        if (!$config->contains($name)) {
            if ($create) {
                $config->add($name, array());
            }
        }
        return $config->itemAt($name);
    }
    
    /**
     * Converts data for the entity to always be as
     *  'name' => array(
     *      'class' => 'path.to.class'
     *  );
     * @param AmConfig $config
     * @param string   $name
     * @return bool true if normalization was needed.
     */
    protected function normalizeConfig($config, $name)
    {
        $key = $config->search($name);
        if (false !== $key) {
            $config->remove($key);
            $config->add($name, array(
                'class' => $this->getOwner()->getFullClassName(),
            ));
            return true;
        } elseif (($options = $config->itemAt($name)) 
                    && !$options->count()
        ) {
            $options->add('class', $this->getOwner()->getFullClassName());
        }
        return false;
    }
    
    /**
     * Finds entity name in the config.
     * @return string
     */
    protected function resolveConfigName()
    {
        $default = $this->getDefaultName();
        $config  = $this->load($default, false);
        if (!$config) {
            $section = $this->loadSection();
            $section->remove($default);
            foreach ($section as $name => $value) {
                if (!is_array($value)) {
                    continue;
                }
                $fullClass = $this->getOwner()->getFullClassName();
                if (isset($value['class']) 
                    && (false !== strpos($fullClass, $value['class']))) {
                    return $name;
                }
            }
        }
        return $default;
    }
}