<?php
/**
 * Works with entities from the extensions section.
 */
class AppManagerExtension extends AppManagerEntity
{   
    protected function getConfigSection()
    {
        return 'components';
    }
    
    protected function getScanDirs()
    {
        return array(
            Yii::app()->getExtensionPath(),
        );
    }
    
    protected function formFullClassName()
    {
        return 'ext.' . $this->getClassName(); 
    }
}
