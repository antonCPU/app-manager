<?php
Yii::import('appManager.models.AmEntityModules');

class AmEntityModulesTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.modules';
        $this->entity = new AmEntityModules($id);
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(1, $this->entity->getChildren());
    }
    
    public function testChild()
    {
        $child = $this->entity->getChild('amAppMock');
        
        $this->assertInstanceOf('AmEntityModule', $child);
        $this->assertEquals($this->entity, $child->getParent());
    }
}