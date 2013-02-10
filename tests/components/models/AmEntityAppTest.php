<?php
Yii::import('appManager.models.AmEntityApp');
Yii::import('appManager.entity.*');

class AmEntityAppTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityApp('appManager.tests.data.app');
    }
    
    public function testTitle()
    {
        $this->assertEquals('App', $this->entity->getTitle());
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(3, $this->entity->getChildren());
    }
    
    public function testChildrenComponents()
    {
        $this->assertInstanceOf('AmEntityComponents', $this->entity->getChild('components'));
    }
    
    public function testChildrenModules()
    {
        $this->assertInstanceOf('AmEntityModules', $this->entity->getChild('modules'));
    }
    
    public function testChildrenExtensions()
    {
        $this->assertInstanceOf('AmEntityExtensions', $this->entity->getChild('extensions'));
    }
    
    public function testIsActive()
    {
        $this->assertTrue($this->entity->isActive());
    }
    
    public function testAttachClassBehavior()
    {
        $this->assertEquals('CWebApplication', $this->entity->getClassName());
    }
    
    public function testAttachConfigBehavior()
    {
        $this->assertTrue($this->entity->isWritable());
    }
    
    public function testSiteName()
    {
        $options = $this->entity->getOptions();
        $name = null;
        foreach ($options as $option) {
            if ('name' === $option->getName()) {
                $name = $option->getValue();
                break;
            }
        }
        $this->assertEquals('Test App Manager', $name);
    }
}