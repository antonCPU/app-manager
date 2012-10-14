<?php

class AppController extends AmEntityController
{
    public $layout = '/layouts/app';
    
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = new AmEntityApp;
        }
        return $this->model;
    }
}