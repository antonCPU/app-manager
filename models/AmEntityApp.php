<?php

class AmEntityApp extends AmEntityModule
{
    public function getId()
    {
        return 'application';
    }
    
    protected function resolvePath()
    {
        return Yii::getPathOfAlias('application');
    }
}
