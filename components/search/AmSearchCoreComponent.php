<?php

class AmSearchCoreComponent extends AmSearchComponent
{    
    public function findById($id)
    {
        $alias = str_replace('system.', '', $id);
        $components = $this->scan();
        $name = array_search($alias, $this->scan());
        if (false === $name) {
            return null;
        }
        $entity = $this->createEntity($id);
        return $entity->setName($name);
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
        );
    }
    
    protected function createEntities($results)
    {
        $entities = array();
        foreach ($results as $name => $id)
        {
            $entity = $this->createEntity('system.' . $id);
            $entity->setName($name);
            $entities[] = $entity;
        }
        return $entities;
    }
}
