<?php

class AmEntityComponent extends AmEntity
{
    public function getConfigSection()
    {
        return 'components';
    }
    
    public function getBaseClass()
    {
        return 'IApplicationComponent';
    }
}
