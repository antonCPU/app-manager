<?php
Yii::import('appManager.components.entity.behaviors.AmClassBehavior');

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
        $this->assertEquals('author', $this->entity->author);
    }
    
    public function testSummary()
    {
        $this->assertEquals('Summary', $this->entity->summary);
    }
    
    public function testDescription()
    {
        $this->assertEquals('Description.', $this->entity->description);
    }
    
    public function testLink()
    {
        $this->assertEquals('http://link', $this->entity->link);
    }
    
    public function testPropertiesNotEmpty()
    {
        $this->assertNotEmpty($this->entity->getProperties());
    }
	
	public function testClassPropertyIsset()
	{
		$this->assertTrue(isset($this->entity->description));
	}
}

class AmEntityClassMock extends AmEntity
{
}