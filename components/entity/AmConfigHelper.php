<?php
/**
 * Performs operations related to an entity configuration.
 */
class AmConfigHelper extends CComponent
{
    /**
     * @var string entity key in config. 
     */
    protected $name;
    /**
     * @var AmEntity
     */
    protected $entity;
    /**
     * @var AmConfig 
     */
    protected $config;
    
    /**
     * @param AmEntity $entity
     */
    public function __construct($entity)
    {
        $this->setEntity($entity);
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
     * @return AmConfigHelper
     */
    protected function setName($name)
    {
        $this->name = $name;
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
     * @param AmEntity $entity
     * @return AmConfigHelper
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    /**
     * Adds entity to the config.
     * @return bool
     */
    public function activate()
    {
        $this->loadSection()->add($this->getName(), array(
            'class' => $this->getEntity()->getFullClassName(),
        ));
        return $this->save();
    }
    
    /**
     * Removes entity from the config.
     * @return bool
     */
    public function deactivate()
    {
        $this->loadSection()->remove($this->getName());
        return $this->save();
    }
    
    /**
     * Restores entity options.
     * @return bool
     */
    public function restore()
    {
        $config = $this->get();
        $config->clear();
        $config->add('class', $this->getEntity()->getFullClassName());
        return $this->save();
    }
    
    /**
     * Updates entity data.
     * @return AmConfigHelper
     */
    public function update()
    {
        $this->updateName();
        $this->get()->add('class', $this->getEntity()->getFullClassName());
        return $this;
    }
    
    /**
     * Saves the config.
     * @return bool
     */
    public function save()
    {
        return AppManagerModule::config()->save();
    }
    
    /**
     * Changes entity name in the config.
     */
    protected function updateName()
    {
        $entity = $this->getEntity();
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
    public function get()
    {
        if (null === $this->config) {
            $this->config = $this->load($this->getName());
        }
        return $this->config;
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
        return !$this->get()->count();
    }
    
    /**
     * Checks if the entity has overriden any options.
     * @return bool
     */
    public function isChanged()
    {
        return $this->get()->count() > 1;
    }
    
    /**
     * Loads a config section related to the entity.
     * @return AmNode
     */
    public function loadSection()
    {
        return AppManagerModule::config($this->getEntity()->getConfigSection());
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
        $current = $config->itemAt($name);
        if (null === $current) {
            if (!$this->normalizeConfig($config, $name)) {
                if ($create) {
                    $config->add($name, array());
                }
            }
            $current = $config->itemAt($name);
        } 
        return $current;
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
                'class' => $this->getEntity()->getFullClassName(),
            ));
            return true;
        }
        return false;
    }
    
    /**
     * Finds entity name in the config.
     * @return string
     */
    protected function resolveConfigName()
    {
        $default = $this->getEntity()->getDefaultName();
        $config = $this->load($default, false);
        if (!$config) {
            $section = $this->loadSection();
            $section->remove($default);
            foreach ($section as $name => $value) {
                if (!is_array($value)) {
                    continue;
                }
                $fullClass = $this->getEntity()->getFullClassName();
                if (isset($value['class']) 
                    && (false !== strpos($fullClass, $value['class']))) {
                    return $name;
                }
            }
        } elseif (!$config->count()) {
            $config->add('class', $this->getEntity()->getFullClassName());
        }
        return $default;
    }
}
