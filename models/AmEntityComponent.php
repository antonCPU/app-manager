<?php

class AmEntityComponent extends AmEntity
{
    public function getConfigSection()
    {
        return 'components';
    }
    
    public function getSearchClass()
    {
        return 'CComponent';
    }
    
    public function getSearchClassExclude()
    {
        return array('CWidget', 'CValidator', 'CController', 'CUserIdentity');
    }
}
