<?php

class AmEntityModule extends AmEntity
{
    protected function formFileName($name)
    {
        return parent::formFileName(ucfirst($name) . 'Module');
    }
}
