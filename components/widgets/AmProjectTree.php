<?php

class AmProjectTree extends CTreeView
{
    public function init()
    {
        Yii::app()->clientScript->registerScript($this->getId(), 'jQuery(function($) {
            var $treeBlock = $(".filetree");

            $treeBlock.on("click", "span", function() {
               var $elem = $(this);
               if ($elem.hasClass("current")) {
                   return false;
               }
               $treeBlock.find("span.current").removeClass("current");

               $elem.addClass("current");
               $treeBlock.trigger("entityClick", $elem.closest("li").attr("id"));
            });
        });');
        
        parent::init();
    }
    
    public static function saveEntityAsJson($entity)
    {
        $tree = array();
        foreach ($entity->getChildren() as $child) {
            if (!$child->asa('config') && !$child->getChildren()) {
                continue;
            }

            $classes   = array();
            $classes[] = ($child instanceof AmEntityComposite) ? 'folder' : 'file';
            $class = str_replace('AmEntity', '', get_class($child));
            $classes[] = self::createHtmlClassName($class);

            if ($child->asa('config') && $child->isActive()) {
                $classes[] = 'active';
            }

            $hasChildren = false;
            foreach ($child->getChildren() as $subChild) {
                if ($subChild->asa('config') || $subChild->getChildren()) {
                    $hasChildren = true;
                    break;
                }
            }
            $tree[] = array(
                'id'		  => $child->getId(),
                'text'		  => $child->getTitle(),
                'hasChildren' => $hasChildren,
                'classes'	  => implode(' ', $classes),
            );
        }
        return self::saveDataAsJson($tree);
	}
	
    /**
     * @string $class class name in the camel case format.
     * @link http://stackoverflow.com/a/1589535
     */
    public static function createHtmlClassName($class)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $class)); 
    }
}