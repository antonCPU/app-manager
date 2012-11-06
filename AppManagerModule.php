<?php
/**
 * AppManager module class file.
 *
 * @author Anton Yakushin <yakushinanton@gmail.com>
 * @version 0.6.2
 * @license BSD
 */
/**
 * AppManager provides user interface to the Yii application config.
 * 
 * AppManager provides ability to browse all installed components, modules, extensions,
 * activate/deactivate them and update their settings. Also the Yii core components
 * and modules have the same abilities.
 * 
 * To activate manager add to Yii configuration 'appManager' under 'modules' section.
 * For proper work the application config file (config/main.php) should be 
 * writable by the server. If it is not, only browsing will be available.
 * NOTICE: it's recommended to backup your config file before performing any
 * actions (except browsing) with AppManager as it will completely override 
 * the config during work.
 * 
 * @author Anton Yakushin <yakushinanton@gmail.com>
 * @link https://github.com/antonCPU/app-manager
 */
class AppManagerModule extends CWebModule
{
    public $defaultController = 'app';
    public $layout = '/layouts/main';
    
    /**
     * @var AmConfig 
     */
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

    /**
     * Gets the config or its section.
     * @param string $path path.to.section
     * @return AmNode
     */
    public static function config($path = null)
    {
        if (null === self::$settings) {
            $config = self::getInstance()->getConfigLocation();
            self::$settings = new AmConfig($config);
        }
        return self::$settings->itemAtPath($path);
    }
    
    /**
     * @return AppManagerModule
     */
    public static function getInstance()
    {
        return Yii::app()->controller->module;
    }

    /**
     * @param string $message
     * @param array  $params
     * @return string
     * @see Yii::t()
     */
    public static function t($message, $params = array())
    {
        return Yii::t('AppManagerModule.core', $message, $params);
    }

    /**
     * Absolute path to the Yii config.
     * @return string
     */
    public function getConfigLocation()
    {
        return Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config/main.php';
    }

    /**
     * @return string
     */
    public function getAssetsUrl()
    {
        if (null === $this->_assetsUrl) {
            $this->_assetsUrl = Yii::app()->getAssetManager()
                    ->publish(Yii::getPathOfAlias('appManager.assets'));
        }
        return $this->_assetsUrl;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getCssUrl($name)
    {
        return $this->getAssetsUrl() . '/css/' . $name . '.css';
    }
}
