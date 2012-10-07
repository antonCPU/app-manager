<?php
/**
 * Common controller.
 */
class SettingsController extends AppManagerController
{
    public $defaultAction = 'list';
    protected $title = 'Settings';
    
    /**
     * Shows list of entities.
     */
    public function actionList()
    { 
        $this->render('list');
    }
}