<?php
Yii::import('appManager.components.entity.*');

class AmEntityCompositeTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $id = 'appManager.tests.data.app.modules.amAppMock';
        $this->entity = new AmEntityCompositeMock($id);
    }
    
    public function testChild()
    {
        $child = $this->entity->getChild('components.AmAppMockModuleComponent');
        $this->assertInstanceOf('AmEntity', $child);
    }
    
    public function testChildren()
    {
        $children = $this->entity->getChildren();
        $this->assertCount(3, $children);
        $this->assertContainsOnlyInstancesOf('AmEntity', $children);
    }
}

class AmEntityCompositeMock extends AmEntityComposite
{
    protected function createChild($id)
    {
        return new self($id, $this);
    }
}