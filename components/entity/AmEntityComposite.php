<?php
/**
 * Represents entities that may have children.
 */
abstract class AmEntityComposite extends AmEntity
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
        if ($results = $this->scan()) {
            return $this->createChildren($results);
        }
        return array();
    }
    
    /**
     * Forms entity instances.
     * @param array $results
     * @return AmEntity[]
     */
    protected function createChildren($results)
    {
        $children = array();
        foreach ($results as $result) {
            if ($child = $this->createChild($result)) {
                $children[] = $child;
            }
        }
        return $children;
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
    abstract protected function createChild($id);
    
    /**
     * Creates an id for a child.
     * @param string $id
     * @return string
     */
    protected function formChildId($id)
    {
        return $this->getId() . '.' . $id;
    }
}
