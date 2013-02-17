<?php
Yii::import('appManager.models.AmEntityCoreModules');

class AmEntityCoreModulesTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityCoreModules('system.modules');
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(1, $this->entity->getChildren());
    }
    
    public function testChildId()
    {
        $this->assertEquals('system.modules.gii', $this->getChild()->getId());
    }
    
    public function testChildName()
    {
        $this->assertEquals('gii', $this->getChild()->getName());
    }
    
    public function testChildClassName()
    {
        $this->assertEquals('GiiModule', $this->getChild()->getClassName());
    }
    
    public function testChildFullClassName()
    {
        $this->assertEquals('system.gii.GiiModule', $this->getChild()->getFullClassName());
    }
    
    protected function getChild()
    {
        return $this->entity->getChild('gii');
    }
}