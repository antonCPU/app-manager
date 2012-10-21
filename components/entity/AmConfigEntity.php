<?php

class AmConfigEntity extends CComponent
{
    protected $name;
    protected $entity;
    protected $config;
    
    public function __construct($entity)
    {
        $this->setEntity($entity);
    }
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    public function activate()
    {
        $this->loadSection()->add($this->getName(), array(
            'class' => $this->getEntity()->getFullClassName(),
        ));
        return $this->save();
    }
    
    public function deactivate()
    {
        $this->loadSection()->remove($this->getName());
        return $this->save();
    }
    
    public function restore()
    {
        $config = $this->get();
        $config->clear();
        $config->add('class', $this->getEntity()->getFullClassName());
        return $this->save();
    }
    
    public function getEntity()
    {
        return $this->entity;
    }
    
    public function getName()
    {
        if (null === $this->name) {
            $this->name = $this->resolveConfigName();
        }
        return $this->name;
    }
    
    protected function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function get()
    {
        if (null === $this->config) {
            $this->config = $this->load($this->getName());
        }
        return $this->config;
    }
    
    public function update()
    {
        $this->updateName();
        $this->get()->add('class', $this->getEntity()->getFullClassName());
        return $this;
    }
    
    public function isWritable()
    {
        return AppManagerModule::config()->isWritable();
    }
    
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
    
    public function isEmpty()
    {
        return !$this->get()->count();
    }
    
    public function isChanged()
    {
        return $this->get()->count() > 1;
    }
    
    public function save()
    {
        return AppManagerModule::config()->save();
    }
    
    public function loadSection()
    {
        return AppManagerModule::config($this->getEntity()->getConfigSection());
    }
    
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
    
    protected function resolveConfigName()
    {
        $default = $this->getEntity()->getDefaultName();
        $config = $this->load($default, false);
        if (!$config) {
            $section = $this->loadSection();
            $section->remove($default);
            foreach ($section as $name => $value) {
                if (isset($value['class']) 
                    && $value['class'] == $this->getEntity()->getFullClassName()) {
                    return $name;
                }
            }
        } elseif (!$config->count()) {
            $config->add('class', $this->getEntity()->getFullClassName());
        }
        return $default;
    }
}
