<?php

class AmEntityApp extends AmEntityModule
{
    protected function resolvePath()
    {
        return Yii::getPathOfAlias('application');
    }
}
