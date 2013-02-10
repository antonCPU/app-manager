<?php
Yii::import('appManager.models.AmEntityComponents');

class AmEntityComponentsTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.components';
        $this->entity = new AmEntityComponents($id);
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(2, $this->entity->getChildren());
    }
    
    public function testApplicationComponentChild()
    {
        $child = $this->entity->getChild('AmAppMockAppComponent');
        
        $this->assertInstanceOf('AmEntityComponent', $child);
        $this->assertTrue($child->isCorrect());
        $this->assertEquals($this->entity, $child->getParent());
    }
    
    public function testComponentChild()
    {
        $child = $this->entity->getChild('AmAppMockComponent');
        $this->assertNull($child);
    }
}