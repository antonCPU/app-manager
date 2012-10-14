<?php

class AmSearch extends CComponent
{
    protected $dir;
    
    public function __construct($dir)
    { 
        $this->setDir($dir);
    }
    
    public function getDir()
    {
        return $this->dir;
    }
    
    protected function setDir($dir)
    {
        $this->dir = $this->resolveDir($dir);
        return $this;
    }
    
    protected function resolveDir($path)
    {
        $fromAlias = Yii::getPathOfAlias($path);
        if (false !== $fromAlias) {
            $path = $fromAlias;
        }
        return $path;
    }
    
    public function perform()
    {
        $results = array();
        if ($results = $this->scan()) {
            $results = $this->createEntities($results);
        }
        return new CArrayDataProvider($results);
    }
    
    protected function scan()
    {
        $results = scandir($this->getDir());
        unset($results[0], $results[1]);
        foreach ($results as &$result) {
            $result = $this->getDir() . DIRECTORY_SEPARATOR . $result;
        }
        return $results;
    }
    
    protected function createEntities($results)
    {
        $entities = array();
        foreach ($results as $result) {
            $entities[] = $this->createEntity($this->createEntityId($result));
        }
        return $entities;
    }
    
    protected function createEntity($id)
    {
        return new AmEntity($id);
    }
    
    protected function createEntityId($path)
    {
        $baseDir = Yii::getPathOfAlias('application');
        $parts = explode('.', $path);
        $dir = $parts[0];
        $dir = str_replace($baseDir, 'application', $dir);
        $id = str_replace(DIRECTORY_SEPARATOR, '-', $dir);
        return $id;
    }
}
