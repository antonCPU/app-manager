<?php

class AmEntitySystemComponents extends AmEntityComposite
{
    protected function scan()
    {
        return array(
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
    
    public function getChild($id)
    { 
        $name = array_search($id, $this->scan());
        if (false === $name) { 
            return null;
        }
        return $this->createChild($id, $name);
    }

    protected function createChildren($results)
    {
        $entities = array();
        foreach ($results as $name => $id) { 
            $entities[] = $this->createChild($id, $name);
        }
        return $entities;
    }
    
    protected function createChild($id, $name = null)
    {
        $id = $this->formChildId($id);
        $entity = new AmEntityComponent($id, $this);
        $entity->config->setDefaultName($name);
        $entity->class->setFullClassName(str_replace('.components', '', $id));
        return $entity;
    }
}