<?php
/**
 * Works with entities from the extensions section.
 */
class AppManagerModuleEntity extends AppManagerEntity
{   
    public function canDeactivate()
    {
        return parent::canDeactivate() && !$this->isSelf();
    }
    
    public function canDelete()
    {
        return parent::canDelete() && !$this->isSelf() && !$this->getIsCore();
    }
    
    public function setName($name)
    {
        return parent::setName($this->correctName($name));    
    }
    
    protected function getConfigSection()
    {
        return 'modules';
    }
    
    protected function getScanDirs()
    {
        return array(
            Yii::getPathOfAlias('application.modules'),
        );
    }
    
    protected function formFileName($name)
    {
        return parent::formFileName(ucfirst($name) . 'Module');
    }
    
    protected function createEntity($path = null)
    {
        return new self($path);
    }
    
    protected function isSelf()
    {
        return ('appManager' === $this->getId());
    }
    
    protected function correctName($name)
    {
        return str_replace('Module', '', $name);
    }
    
    protected function getCoreList()
    {
        return array(
            'gii' => 'gii.GiiModule',
        );
    }
    
    protected function formFullClassName()
    { 
        return 'application.modules.' . $this->getId() . '.' . $this->getClassName(); 
    }
}
