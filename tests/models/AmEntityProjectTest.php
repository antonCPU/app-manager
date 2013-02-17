<?php
Yii::import('appManager.models.AmEntityProject');

class AmEntityProjectTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityProject();
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(2, $this->entity->getChildren());
    }
    
    public function testApplicationChild()
    {
        $this->assertInstanceOf('AmEntityApplication', $this->entity->getChild('application'));
    }
    
    public function testCoreChild()
    {
        $this->assertInstanceOf('AmEntitySystem', $this->entity->getChild('system'));
    }
}