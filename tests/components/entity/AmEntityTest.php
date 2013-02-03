<?php
Yii::import('appManager.components.entity.AmEntity');

class AmEntityTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityMock();
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
}

class AmEntityMock extends AmEntity
{
    
}