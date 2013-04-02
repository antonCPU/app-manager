<?php

class AmEntityComponent extends AmEntity
{
    public function behaviors()
    {
        return array(
            'config' => array(
                'class'   => 'appManager.components.entity.behaviors.AmConfigBehavior',
                'section' => 'components',
            ),
            'options' => array(
                'class' => 'appManager.components.entity.behaviors.AmOptionsBehavior',
            ),
            'class' => array(
                'class' => 'appManager.components.entity.behaviors.AmClassBehavior',
                'baseClass' => 'IApplicationComponent',
            ),
        );
    }
}
