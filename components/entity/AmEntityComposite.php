<?php
/**
 * Represents entities that may have children.
 */
class AmEntityComposite extends AmEntity
{
    /**
     * Performs scanning inside the base directory.
     * @return array list of found php files or directories.
     */
    protected function scan()
    {
        if (!$results = glob($this->getPath() . '/*')) {
            return array();
        }
        foreach ($results as &$result) {
            $result = basename($result, '.php');
        }
        return $results;
    }
    
    /**
     * @return AmEntity[]
     */
    public function getChildren()
    {
        $results = array();
        if ($results = $this->scan()) {
            $results = $this->createChildren($results);
        }
        return $results;
    }
    
    /**
     * Forms entity instances.
     * @param array $results
     * @return AmEntity[]
     */
    protected function createChildren($results)
    {
        $entities = array();
        foreach ($results as $result) {
            $child = $this->createChild($result);
            if ($child->isCorrect()) {
                $entities[] = $child;
            }
        }
        return $entities;
    }
    
    /**
     * Finds a child.
     * @param string $id
     * @return AmEntity|null
     */
    public function getChild($id)
    { 
        $parts = explode('.', $id);
        $childId = array_shift($parts);
        $child = $this->createChild($childId);
        if ($parts) {
            $id = implode('.', $parts);
            $child = $child->getChild($id);
        }
        return $child;
    }
    
    /**
     * Creates an entity.
     * @param string $id Yii alias.
     * @return AmEntity
     */
    protected function createChild($id)
    {
        $entity = new self;
        return $entity->setParent($this)->setId($id);
    }

    /**
     * @return CArrayDataProvider
     */
    public function getChildrenProvider()
    {
        $provider = parent::getChildrenProvider();
        $provider->sort = array(
            'defaultOrder'=>'isActive DESC',
        );
        return $provider;
    }
}
