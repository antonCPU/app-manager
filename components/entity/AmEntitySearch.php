<?php

class AmEntitySearch extends AmEntityComposite
{
    public function getChildren()
    {
        $results = array();
        if ($results = $this->scan()) {
            $results = $this->createChildren($results);
        }
        return $results;
    }
    
    protected function scan()
    {
        $results = scandir($this->getPath());
        unset($results[0], $results[1]);
        foreach ($results as &$result) {
            $result = basename($result, '.php');
        }
        return $results;
    }
    
    protected function createChildren($results)
    {
        $entities = array();
        foreach ($results as $result) {
            $entities[] = $this->createChild($result);
        }
        return $entities;
    }
}
