<?php
/**
 * Common controller.
 */
class SettingsController extends AppManagerController
{
    public $defaultAction = 'update';
    protected $title = 'Settings';
    
    /**
     * Shows list of entities.
     */
    public function actionUpdate()
    { 
        $model = new AppManagerApplication;
        $this->render('update', array(
            'model' => $model,
        ));
    }
}