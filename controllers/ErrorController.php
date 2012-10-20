<?php
/**
 * Handles error requests.
 */
class ErrorController extends AmController
{
    public $defaultAction = 'show';
    protected $title = 'Error';
    
    public function actionShow()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('show', $error);
	    }
	}
}
