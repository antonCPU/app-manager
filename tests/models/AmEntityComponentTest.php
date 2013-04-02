<?php
Yii::import('appManager.models.AmEntityComponent');

class AmEntityComponentTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.components.AmAppMockAppComponent';
        $this->entity = new AmEntityComponent($id);
    }
    
    public function testTitle()
    {
        $this->assertEquals('AmAppMockAppComponent', $this->entity->getTitle());
    }
    
    public function testAttachClassBehavior()
    {
        $this->assertEquals('AmAppMockAppComponent', $this->entity->getClassName());
    }
    
    public function testAttachConfigBehavior()
    {
        $this->assertFalse($this->entity->isActive());
    }
    
    public function testAttachOptionsBehavior()
    {
        $this->assertNotEmpty($this->entity->getOptions());
    }
    
    public function testCorrect()
    {
        $this->assertTrue($this->entity->isCorrect());
        
        $entity = new AmEntityComponent('appManager.tests.data.app.components.AmAppMockComponent');
        $this->assertFalse($entity->isCorrect());
        
        $entity = new AmEntityComponent('appManager.tests.data.app.components.complexComponent');
        $this->assertTrue($entity->isCorrect());
    }
}