<?php
/**
 * Provides functionality to operate with settings tree.
 */
class AmNode extends CConfiguration
{
    /**
     * Gets an item for key.
     * @param string $key
     * @return mixed if the item is an array then it will be transformed to node.
     */
    public function itemAt($key)
    {
        $result = parent::itemAt($key);
        if (is_array($result)) {
            $result = new self($result);
            $this->add($key, $result);
        }
        return $result;
    }
    
    /**
     * Gets an item using path.
     * @param string $path keys separated with '.'.
     * @return mixed will be the last item that could be get using 
     *               AppManagerNode::itemAt().
     */
    public function itemAtPath($path)
    {
        if (!$path || !is_string($path)) {
            return $this;
        }
        $container = $this; 
        foreach (explode('.', $path) as $step) {
            if ($container) {
                $container = $container->itemAt($step);
            } else {
                break;
            }
        } 
        return $container;
    }
    
    /**
     * One level deep search for an item key by value.
     * @param mixed $value
     * @return mixed
     * @see array_search() for return values. 
     */
    public function search($value)
    {
        return array_search($value, $this->toArray());
    }
}
