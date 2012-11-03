<?php
/**
 * Handles requests for entities that belong ot Yii core.
 */
class CoreController extends AmEntityController
{
    protected $title = 'Core';
    
    protected function createModel()
    {
        return new AmEntityCore;
    }
    
    public function actionModules()
    {
        $this->render('view', array(
           'entity' => $this->getModel()->getChild('modules.gii'),
        ));
    }
    
    public function getEntity()
    {
        if (!$this->getQuery('id')) {
            $_GET['id'] = 'system.components';
        }
        return parent::getEntity();
    }
}