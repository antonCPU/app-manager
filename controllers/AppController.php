<?php

class AppController extends AmEntityController
{
    protected $title = 'App';
    
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = new AmEntityApp;
        }
        return $this->model;
    }
}
