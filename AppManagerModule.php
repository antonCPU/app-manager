<?php
        
class AppManagerModule extends CWebModule
{
    public $defaultController = 'app';
    public $layout = '/layouts/main';
    
    protected static $settings;
    private $_assetsUrl;
 
    public function init()
    {
        //custom error action
        Yii::app()->getErrorHandler()->errorAction = '/appManager/error/show';

        // import the module-level models and components
        $this->setImport(array(
            'appManager.models.*',
            'appManager.components.*',
            'appManager.components.parser.*',
            'appManager.components.entity.*',
        ));
        
        $this->setAliases(array(
            'AmWidgets' => 'appManager.components.widgets',
        ));
    }

    public function getName()
    {
        return self::t('App Manager');
    }

    public static function config($path = null)
    {
        if (null === self::$settings) {
            $config = self::getInstance()->getConfigLocation();
            self::$settings = new AmConfig($config);
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
        return Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config/main.php';
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
}
