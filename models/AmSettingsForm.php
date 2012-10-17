<?php

class AmSettingsForm extends AmModel
{
    protected $attributes;
    
    public function rules()
    {
        return array(
          array('name', 'required'),
        );
    }
    
    public function setPreload($preload)
    {
        $this->attributes['preload'] = array_map('trim', explode(',', $preload));
    }
    
    public function getPreload()
    { 
        return implode(', ', $this->getConfigValue('preload')->toArray());
    }
    
    public function getName()
    {
        return $this->getConfigValue('name');
    }

    public function setName($name)
    {
        $this->attributes['name'] = $name;
    }
    
    public function setImport($import) 
    {
        $this->attributes['import'] = array_map('trim', explode("\n", $import));
    }
    
    public function getImport()
    {
        return implode("\n", $this->getConfigValue('import')->toArray());
    }
    
    public function getParams()
    {
        $params = $this->getConfigValue('params');
        $result = array();
        foreach ($params as $key => $value) {
            $result[] = $key . ': ' . $value;
        }
        return implode("\n", $result);
    }
    
    public function setParams($params)
    {
        $params = array_map('trim', explode("\n", $params));
        $result = array();
        foreach ($params as $line) {
            list($key, $value) = array_map('trim', explode(':', $line));
            $result[$key] = $value;
        }
        $this->attributes['params'] = $result;
    }
    
    public function save()
    {
        $config = $this->getConfig();
        foreach ($this->attributes as $name => $value) {
            $config->add($name, $value);
        }
        return $config->save();
    }
    
    protected function getConfig()
    {
        return AppManagerModule::config();
    }
    
    protected function getConfigValue($name)
    {
        return $this->getConfig()->itemAt($name);
    }
}
