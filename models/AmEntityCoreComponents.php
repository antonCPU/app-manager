<?php

class AmEntityCoreComponents extends AmEntityComponents
{
    public function getChild($id)
    { 
        $components = $this->scan();
        $name = array_search($id, $components);
        if (false === $name) {
            return null;
        }
        return $this->createChild($id);
    }
    
    protected function scan()
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
            'log'               => 'logging.CLogRouter',
        );
    }
    
    protected function createChildren($results)
    {
        $entities = array();
        foreach ($results as $name => $id)
        { 
            $entities[] = $this->createChild($id);
        }
        return $entities;
    }
    
    protected function createChild($id, $name = null)
    {
        $entity = parent::createChild($id);
        $entity->setName($name);
        $entity->setFullClassName($this->getParent()->getId() . '.' . $id);
        return $entity;
    }
}