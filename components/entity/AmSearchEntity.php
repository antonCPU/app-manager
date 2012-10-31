<?php
/**
 * Helper that searhes for a file that contains entity.
 */
class AmSearchEntity extends CComponent
{
    /**
     * @var AmEntity 
     */
    protected static $entity;
    
    /**
     * Finds related to entity class.
     * @param AmEntity $entity
     * @return string|bool full alias of class or false.
     */
    public static function resolve($entity)
    {
        self::$entity = $entity;
        $id = $entity->getId();
        $file = $entity->getPath() . '.php';
        if (!is_file($file)) {
            foreach ($entity->getSearchPattern() as $rule) {
                if ($files = self::getByRule($rule)) {
                    foreach ($files as $file) {
                        if (self::checkParent($file)) {
                            return $id . '.' . basename($file, '.php');
                        }
                    }
                } 
            }
        } elseif (self::checkParent($file)) {
            return $id;
        }
        return false;
    }
    
    /**
     * Searches files by certain rule.
     * @param string $rule
     * @return array
     */
    protected static function getByRule($rule)
    {
        return glob(self::$entity->getPath() . DIRECTORY_SEPARATOR . $rule);
    }
    
    /**
     * Checks if class that located in the file has needed parents.
     * @param string $file absolute path.
     * @return bool
     */
    protected static function checkParent($file)
    {
        $parser = new AmParser($file);
        
        if ($include = self::$entity->getBaseClass()) {
            if (!$parser->isSubclassOf($include)) {
                return false;
            }
        }
        return true;
    }
}
