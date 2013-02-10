<?php
Yii::import('appManager.models.AmEntityModule');

class AmEntityComponentTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.modules.amAppMock';
        $this->entity = new AmEntityModule($id);
    }
    
    public function testTitle()
    {
        $this->assertEquals('AmAppMock', $this->entity->getTitle());
    }
    
    public function testChildren()
    {
        $this->assertCount(2, $this->entity->getChildren());
    }
    
    public function testChildComponents()
    {
        $this->assertInstanceOf('AmEntityComponents', $this->entity->getChild('components'));
    }
    
    public function testChildModules()
    {
        $this->assertInstanceOf('AmEntityModules', $this->entity->getChild('modules'));
    }
    
    public function testAttachClassBehavior()
    {
        $this->assertEquals('AmAppMockModule', $this->entity->getClassName());
    }
    
    public function testAttachConfigBehavior()
    {
        $this->assertFalse($this->entity->isActive());
    }
    
    public function testCorrect()
    {
        $this->assertTrue($this->entity->isCorrect());
    
        $entity = new AmEntityComponent('appManager.tests.data.app');
        $this->assertFalse($entity->isCorrect());
    }
}