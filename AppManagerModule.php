<?php

class AppManagerModule extends CWebModule
{
    public $defaultController = 'module';
    public $config = 'config/main.php';
    public $layout = '/layouts/main';
    public $errorAction = '/appManager/error/show';
    
    protected static $settings;
    private $_assetsUrl;
 
	public function init()
	{
        //custom error action
        Yii::app()->getErrorHandler()->errorAction = $this->errorAction;
        
		// import the module-level models and components
		$this->setImport(array(
			'appManager.models.*',
			'appManager.components.*',
            'appManager.components.parser.*',
		));
	}

    public function getName()
	{
        return self::t('App Manager');
    }
    
	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action)) {
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		} else {
			return false;
        }
	}
    
    public static function config($path = null)
    {
        if (null === self::$settings) {
            $config = self::getInstance()->getConfigLocation();
            self::$settings = new AppManagerConfig($config);
        }
        return self::$settings->itemAtPath($path);
    }
    
    public static function getInstance()
    {
        return Yii::app()->controller->module;
    }
    
    public static function t($message, $params = array())
    {
        return Yii::t('AppManagerModule.core', $message, $params);
    }
    
    public function getConfigLocation()
    {
        return Yii::app()->basePath . DIRECTORY_SEPARATOR . $this->config;
    }
    
    public function getAssetsUrl()
    {
        if (null === $this->_assetsUrl) {
            $this->_assetsUrl = Yii::app()->getAssetManager()
                    ->publish(Yii::getPathOfAlias('appManager.assets'));
        }
        return $this->_assetsUrl;
    }
    
    public function getCssUrl($name)
    {
        return $this->getAssetsUrl() . '/css/' . $name . '.css';
    }
    
    public function getJsUrl($name)
    {
        return $this->getAssetsUrl() . '/js/' . $name . '.js';
    }
    
    public function registerJs($name)
    {
        Yii::app()->clientScript->registerScriptFile($this->getJsUrl($name));
    }
}
