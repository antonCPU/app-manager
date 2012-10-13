<?php

class AmSearchComponent extends AmSearch
{
    protected function resolveDir($path)
    {
        return parent::resolveDir($path) . '/components';
    }
}
