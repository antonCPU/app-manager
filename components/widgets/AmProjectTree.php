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
			$isComposite = $child instanceof AmEntityComposite;
            if ($isComposite && !$child->getChildren() && !$child->asa('config')) {
				continue;
			}
			$classes   = array();
            $classes[] = ($isComposite) ? 'folder' : 'file';
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
}