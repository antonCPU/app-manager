<?php
Yii::import('appManager.models.AmEntityComponent');

class AmEntityComponentTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityComponent();
        $this->entity->setId('appManager.tests.data.app.components.AmAppComponentMock');
    }
    
    public function testId()
    {
        $this->assertEquals('appManager.tests.data.app.components.AmAppComponentMock', $this->entity->getId());
    }
    
    public function testTitle()
    {
        $this->assertEquals('AmAppComponentMock', $this->entity->getTitle());
    }
    
    public function testParent()
    {
        $this->assertNull($this->entity->getParent());
    }
    
    public function testChildren()
    {
        $this->assertEquals(array(), $this->entity->getChildren());
    }
    
    public function testIsActive()
    {
        $this->assertFalse($this->entity->isActive());
    }
    
    public function testName()
    {
        $this->assertEquals('amAppComponentMock', $this->entity->getName());
    }
    
    public function testActivate()
    {
        $this->entity->activate();
        $this->assertTrue($this->entity->isActive());
    }
    
    public function testDeactivate()
    {
        $this->entity->deactivate();
        $this->assertFalse($this->entity->isActive());
    }
}