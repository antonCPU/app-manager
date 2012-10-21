<?php

class AmSettingsForm extends CFormModel
{
    protected $settings;
    
    public function rules()
    {
        return array(
            array('name', 'required'),
        );
    }
    
    public function __get($name)
    {
        $settings = $this->getSettings();
        if (isset($settings[$name])) {
            return $settings[$name];
        }
        return parent::__get($name);
    }
    
    public function setAttributes($values, $safeOnly=true)
    {
        foreach ($values as $name => $value) {
            $this->settings[$name] = $value;
        }
    }
    
    public function getName()
    {
        if (!isset($this->settings['name'])) {
            $this->settings['name'] = $this->getConfigValue('name');
        }
        return $this->settings['name'];
    }
    
    public function setName($name)
    {
        $this->settings['name'] = $name;
    }
    
    public function getSettings()
    {
        if (null === $this->settings) {
            foreach ($this->getSettingsFormat() as $name => $params) {
                $data = $this->getConfigValue($name);
                $this->settings[$name] = $this->formatSettings($data, $params);
            }
        }
        return $this->settings;
    }
    
    protected function formatSettings($data, $params)
    {
        $sep    = null;
        $keySep = null;
        if (is_string($params)) {
            $sep = $params;
        } elseif (is_array($params)) {
            $sep = $params[0];
            $keySep = isset($params[1]) ? $params[1] : null;
        }
        if ($data && !is_string($data)) {
            $data = $data->toArray();
        }
        return new AmDataView($data, $sep, $keySep);
    }
    
    public function save()
    {
        $config = $this->getConfig();
        foreach ($this->getSettingsFormat() as $name => $params) {
            $value = $this->formatSettings($this->$name, $params)->toArray();
            $config->add($name, $value);
        }
        $config->add('name', $this->getName());
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
    
    protected function getSettingsFormat()
    {
        return array(
            'preload' => ',',
            'import'  => "\n",
            'params'  => array("\n", ':'),
        );
    }
}
