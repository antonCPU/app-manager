<?php

class AmEntityCore extends AmEntity
{
    public function getComponents()
    {
        $search = new AmSearchCoreComponent('system');
        return $search->perform();
    }
    
    public function getModules()
    {
        $search = new AmSearchCoreModule('system');
        return $search->perform();
    }
}
