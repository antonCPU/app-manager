<?php

class AmEntityModule extends AmEntityComposite
{
    protected function formClassName($path)
    {
        return ucfirst(parent::formClassName($path)) . 'Module';
    }
   
    protected function createSearch($section)
    {
        $sections = $this->getSearchSections(); 
        $className = 'AmSearch' . ucfirst($sections[$section]);
        $this->section = $section;
        return new $className($this->getPath() . DIRECTORY_SEPARATOR . $section);
    }
    
    protected function getSearchSections()
    {
        return array(
            'components'  => 'component',
            'modules'     => 'module',
            'extensions'  => 'component',
        );
    }
    
    protected function getConfigSection()
    {
        return 'modules';
    }
}
