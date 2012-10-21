<?php

class AmSettingsForm extends CFormModel
{
    protected $settings;
    protected $text;
    
    public function rules()
    {
        return array(
            array('name', 'required'),
            array('preload, import, params', 'safe'),
        );
    }
    
    public function getPreload()
    { 
        if (!isset($this->text['preload'])) {
            $this->text['preload'] = implode(', ', $this->getConfigArray('preload'));
        } 
        return $this->text['preload'];
    }
    
    public function setPreload($preload)
    {
        $this->text['preload'] = $preload;
        $this->settings['preload']= $this->explode(',', $preload);
    }
    
    public function getName()
    {
        if (!isset($this->text['name'])) {
            $this->text['name'] = $this->getConfigValue('name');
        } 
        return $this->text['name'];
    }

    public function setName($name)
    {
        $this->text['name'] = $name;
        $this->settings['name'] = $name;
    }
    
    public function getImport()
    {
        if (!isset($this->text['import'])) {
            $this->text['import'] = implode("\n", $this->getConfigArray('import'));
        }
        return $this->text['import'];
    }
    
    public function setImport($import) 
    {
        $this->text['import'] = $import;
        $this->settings['import'] = $this->explode("\n", $import);
    }
    
    public function getParams()
    {
        if (!isset($this->text['params'])) {
            $params = $this->getConfigArray('params');
            $result = array();
            foreach ($params as $key => $value) {
                $result[] = $key . ': ' . $value;
            }
            $this->text['params'] = implode("\n", $result);
        }
        return $this->text['params'];
    }
    
    public function setParams($params)
    {
        $this->text['params'] = $params;
        $params = $this->explode("\n", $params);
        $result = array();
        foreach ($params as $line) {
            if (($values = $this->explode(':', $line)) && 2 === count($values)) {
                $result[$values[0]] = $values[1];
            }
        } 
        $this->settings['params'] = $result;
    }
    
    public function save()
    {
        if (!$this->validate()) {
            return false;
        } 
        $config = $this->getConfig();
        foreach ($this->settings as $name => $value) {
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
        return $this->settings[$name] = $this->getConfig()->itemAt($name);
    }
    
    protected function getConfigArray($name)
    {
        return $this->getConfigValue($name)->toArray();
    }
    
    protected function explode($sep, $data)
    {
        return array_filter(array_map('trim', explode($sep, (string)$data)));
    }
}
