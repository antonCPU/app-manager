<?php
Yii::import('appManager.models.AmEntitySystem');
Yii::import('appManager.entity.*');

class AmEntitySystemTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntitySystem('system');
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
        $this->assertInstanceOf('AmEntitySystemComponents', $this->entity->getChild('components'));
    }
    
    public function testChildrenModules()
    {
        $this->assertInstanceOf('AmEntitySystemModules', $this->entity->getChild('modules'));
    }
}