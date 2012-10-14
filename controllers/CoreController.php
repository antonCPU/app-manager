<?php

class CoreController extends AmEntityController
{
    public $layout = '/layouts/core';
    
    public function getModel()
    {
        if (null === $this->model) {
            $this->model = new AmEntityCore;
        }
        return $this->model;
    }
}