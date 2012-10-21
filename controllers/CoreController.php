<?php

class CoreController extends AmEntityController
{
    protected $title = 'Core';
    
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = new AmEntityCore;
        }
        return $this->model;
    }
    
    public function getMenu()
    {
        $menu = parent::getMenu();
        array_pop($menu);
        return $menu;
    }
}