<?php
/**
 * Works with entities from the components section.
 */
class AppManagerComponent extends AppManagerEntity
{  
    public function canActivate()
    {
        return parent::canActivate() && !$this->getIsCore();
    }
    
    public function canDeactivate()
    {
        return parent::canDeactivate() && !$this->getIsCore();
    }
    
    public function canDelete()
    {
        return parent::canDelete() && !$this->getIsCore();
    }
    
    public function canUpdate()
    {
        return parent::canUpdate() || $this->getIsCore();
    }
    
    protected function getConfigSection()
    {
        return 'components';
    }
    
    protected function getScanDirs()
    { 
        return array(
            Yii::getPathOfAlias('application.components'),
        );
    }
    
    protected function getCoreList()
    {
        return array(
            'coreMessages'      => 'i18n.CPhpMessageSource',
            'db'                => 'db.CDbConnection',
            'messages'          => 'i18n.CPhpMessageSource',
            'errorHandler'      => 'base.CErrorHandler',
            'securityManager'   => 'base.CSecurityManager',
            'statePersister'    => 'base.CStatePersister',
            'urlManager'        => 'web.CUrlManager',
            'request'           => 'web.CHttpRequest',
            'format'            => 'utils.CFormatter',
            'session'           => 'web.CHttpSession',
            'assetManager'      => 'web.CAssetManager',
            'user'              => 'web.auth.CWebUser',
            'themeManager'      => 'web.CThemeManager',
            'authManager'       => 'web.auth.CAuthManager',
            'clientScript'      => 'web.CClientScript',
            'widgetFactory'     => 'web.CWidgetFactory',
        );
    }
    
    protected function formFullClassName()
    {
        return 'application.components.' . $this->getClassName(); 
    }
}