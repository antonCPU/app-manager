<?php
Yii::import('appManager.models.AmEntityComponent');

class AmEntityComponentTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.components.AmAppMockComponent';
        $this->entity = new AmEntityComponent($id);
    }
    
    public function testTitle()
    {
        $this->assertEquals('AmAppMockComponent', $this->entity->getTitle());
    }
    
    public function testAttachClassBehavior()
    {
        $this->assertEquals('AmAppMockComponent', $this->entity->getClassName());
    }
    
    public function testAttachConfigBehavior()
    {
        $this->assertFalse($this->entity->isActive());
    }
    
    public function testCurrentCorrect()
    {
        $this->assertTrue($this->entity->isCorrect());
    }
    
    public function testAnotherEntityNotCorrect()
    {
        $entity = new AmEntityComponent('appManager.tests.data.app');
        $this->assertFalse($entity->isCorrect());
    }
}