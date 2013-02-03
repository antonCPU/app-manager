<?php
Yii::import('appManager.components.entity.AmClassBehavior');

class AmClassBehaviorTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityMock();
        $this->entity->attachBehavior('class', new AmClassBehavior);
    }
    
    public function testFileName()
    {
        $this->assertEquals(AM_DATA_DIR . '/app/components/AmAppComponentMock.php', $this->entity->getFileName());
    }
    
    public function testClassName()
    {
        $this->assertEquals('AmAppComponentMock', $this->entity->getClassName());
    }
    
    public function testFullClassName()
    {
        $this->assertEquals('appManager.tests.data.app.components.AmAppComponentMock', $this->entity->getFullClassName());
    }
    
    public function testPath()
    {
        $this->assertEquals(AM_DATA_DIR . '/app/components/AmAppComponentMock.php', $this->entity->getFileName());
    }
    
    public function testClassInfoNotEmpty()
    {
        $this->assertNotEmpty($this->entity->getClassInfo());
    }
}

class AmEntityMock extends CComponent
{
    public function getId()
    {
        return 'appManager.tests.data.app.components.AmAppComponentMock';
    }
}