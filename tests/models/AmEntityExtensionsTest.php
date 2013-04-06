<?php
Yii::import('appManager.models.AmEntityExtensions');

class AmEntityExtensionsTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.extensions';
        $this->entity = new AmEntityExtensions($id);
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(2, $this->entity->getChildren());
    }
    
    public function testComponentChild()
    {
        $child = $this->entity->getChild('AmAppMockExtension');
        
        $this->assertInstanceOf('AmEntityComponent', $child);
        $this->assertTrue($child->isCorrect());
        $this->assertEquals($this->entity, $child->getParent());
    }
    
    public function testComplexComponentChild()
    {
        $child = $this->entity->getChild('complexExtension');
        
        $this->assertInstanceOf('AmEntityComponent', $child);
        $this->assertTrue($child->isCorrect());
        $this->assertEquals($this->entity, $child->getParent());
    }
}