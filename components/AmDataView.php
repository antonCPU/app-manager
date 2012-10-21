<?php

class AmDataView extends CComponent
{
    protected $data;
    protected $sep;
    protected $keySep;
    
    public function __construct($data, $sep = null, $keySep = null)
    {
        $this->data = $data;
        $this->sep = $sep;
        $this->keySep = $keySep;
    }
    
    public function __toString()
    {
        return $this->toString();
    }
    
    public function toString()
    {
        $result = '';
        if (is_string($this->data)) {
            $result = $this->data;
        } elseif (is_array($this->data)) {
            $list = array();
            if ($this->keySep) {
                foreach ($this->data as $key => $value) {
                    $list[] = $key . $this->keySep . $value;
                }
            } else {
                $list = $this->data;
            }
            $result = implode($this->sep, $list);
        }
        return $result;
    }
    
    public function toArray()
    {
        $result = array();
        if (is_array($this->data)) {
            $result = $this->data;
        } else {
            $data = (string)$this->data;
            $result = $this->explode($this->sep, $data);
            if ($this->keySep) {
                $list = array();
                foreach ($result as $set) {
                    $data = $this->explode($this->keySep, $set);
                    if (2 === count($data)) {
                        $list[$data[0]] = $data[1];
                    }
                }
                $result = $list;
            }
        }
        return $result;
    }
    
    protected function explode($sep, $data)
    {
        return array_filter(array_map('trim', explode($sep, $data)));
    }
}
