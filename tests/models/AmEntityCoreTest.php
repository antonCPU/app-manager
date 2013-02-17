<?php
Yii::import('appManager.models.AmEntityCore');
Yii::import('appManager.entity.*');

class AmEntityCoreTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityCore('system');
    }
    
    public function testTitle()
    {
        $this->assertEquals('Core', $this->entity->getTitle());
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(2, $this->entity->getChildren());
    }
    
    public function testChildrenComponents()
    {
        $this->assertInstanceOf('AmEntityCoreComponents', $this->entity->getChild('components'));
    }
    
    public function testChildrenModules()
    {
        $this->assertInstanceOf('AmEntityCoreModules', $this->entity->getChild('modules'));
    }
}