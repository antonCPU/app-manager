<?php

class AmEntityComponent extends AmEntity
{
    public function behaviors()
    {
        return array(
            'config' => array(
                'class'   => 'appManager.components.entity.AmConfigBehavior',
                'section' => 'components',
            )
        );
    }

    public function getBaseClass()
    {
        return 'IApplicationComponent';
    }
}
