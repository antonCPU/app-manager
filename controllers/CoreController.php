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
    
    public function actionDelete($id)
    {
        $entity = $this->getEntity();
        $this->setEntityFlash('error', 'Unable to delete a core class.');
        $this->redirect(array($this->getSection()));
    }
}