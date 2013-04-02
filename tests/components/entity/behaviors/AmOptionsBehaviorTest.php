<?php
Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.entity.behaviors.AmOptionsBehavior');
Yii::import('appManager.components.parser.AmClassInfo');

class AmOptionsBehaviorTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityOptionsMock('appManager.tests.data.app.components.AmMockOptionsComponent');
        $this->entity->attachBehavior('config', new AmOptionsBehavior());
    }
    
    public function testGetOptions()
    {
        $this->assertInstanceOf('AmOptions', $this->entity->getOptions());
    }
    
    public function testGetTextProperty()
    {
        $this->assertEquals("'text'", $this->entity->getOptions()->propertyText);
    }
    
    public function testGetConfigProperty()
    {
        $this->assertEquals("1", $this->entity->getOptions()->propertyInt);
    }
    
    public function testLoop()
    {
        foreach ($this->entity->getOptions() as $option) {
            $this->assertInstanceOf('AmOption', $option);
        }
    }
    
    public function testSetOptions()
    {
        $this->assertTrue($this->entity->setOptions(array(
            'propertyText' => "'new text'",
        )));
        
        $this->assertEquals("'new text'", $this->entity->getOptions()->propertyText);
    }
}

class AmEntityOptionsMock extends AmEntity
{
    public function getConfig()
    {
        return new AmNode(array(
            'propertyInt' => 1,
        ));
    }
    
    public function getProperties()
    {
        $classInfo = new AmClassInfo(Yii::getPathOfAlias($this->getId()) . '.php');
        return $classInfo->getProperties();
    }
}