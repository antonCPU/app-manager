<?php
Yii::import('appManager.components.entity.AmClassBehavior');

class AmClassBehaviorTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityClassMock('appManager.tests.data.app.components.AmAppMockComponent');
        $this->entity->attachBehavior('class', new AmClassBehavior);
    }
    
    public function testFileName()
    {
        $this->assertEquals(AM_DATA_DIR . '/app/components/AmAppMockComponent.php', $this->entity->getFileName());
    }
    
    public function testClassName()
    {
        $this->assertEquals('AmAppMockComponent', $this->entity->getClassName());
    }
    
    public function testFullClassName()
    {
        $this->assertEquals('appManager.tests.data.app.components.AmAppMockComponent', $this->entity->getFullClassName());
    }
    
    public function testPath()
    {
        $this->assertEquals(AM_DATA_DIR . '/app/components/AmAppMockComponent.php', $this->entity->getFileName());
    }
    
    public function testClassInfoNotEmpty()
    {
        $this->assertNotEmpty($this->entity->getClassInfo());
    }
    
    public function testAuthor()
    {
        $this->assertEquals('author', $this->entity->class->author);
    }
    
    public function testSummary()
    {
        $this->assertEquals('Summary', $this->entity->class->summary);
    }
    
    public function testDescription()
    {
        $this->assertEquals('Description.', $this->entity->class->description);
    }
    
    public function testLink()
    {
        $this->assertEquals('http://link', $this->entity->class->link);
    }
    
    public function testPropertiesNotEmpty()
    {
        $this->assertNotEmpty($this->entity->getProperties());
    }
}

class AmEntityClassMock extends AmEntity
{
}