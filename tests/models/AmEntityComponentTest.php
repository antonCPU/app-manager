<?php
Yii::import('appManager.models.AmEntityComponent');

class AmEntityComponentTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityComponent();
        $this->entity->setId('appManager.tests.data.app.components.AmAppMockComponent');
    }
    
    public function testId()
    {
        $this->assertEquals('appManager.tests.data.app.components.AmAppMockComponent', $this->entity->getId());
    }
    
    public function testTitle()
    {
        $this->assertEquals('AmAppMockComponent', $this->entity->getTitle());
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
        $this->assertEquals('amAppMockComponent', $this->entity->getName());
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
    
    public function testAuthor()
    {
        $this->assertEquals('author', $this->entity->getAttribute('author'));
    }
    
    public function testSummary()
    {
        $this->assertEquals('Summary', $this->entity->getAttribute('summary'));
    }
    
    public function testDescription()
    {
        $this->assertEquals('Description.', $this->entity->getAttribute('description'));
    }
    
    public function testLink()
    {
        $this->assertEquals('http://link', $this->entity->getAttribute('link'));
    }
}