<?php

class CoreController extends AmEntityController
{
    protected $title = 'Core';
    
    protected function createModel()
    {
        return new AmEntityCore;
    }
    
    public function getMenu()
    {
        $menu = parent::getMenu();
        array_pop($menu);
        return $menu;
    }
}