<?php
Yii::import('appManager.components.entity.*');

class AmConfigBehaviorTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->createConfig();
        $this->entity = new AmEntityMock();
        $this->entity->attachBehavior('config', new AmConfigBehavior);
    }
    
    protected function createConfig()
    {
        $source = AM_DATA_DIR . '/config.php';
        $file   = AM_DATA_DIR . '/app/config/main.php';
        @copy($source, $file);
    }
    
    public function testCanActivate()
    {
        $this->assertTrue($this->entity->canActivate());
    }
    
    public function testCanDeactivate()
    {
        $this->assertFalse($this->entity->canDeactivate());
    }
    
    public function testCanUpdate()
    {
        $this->assertTrue($this->entity->canUpdate());
    }
    
    public function testCanRestore()
    {
        $this->assertFalse($this->entity->canRestore());
    }
    
    public function testActivate()
    {
        $this->entity->activate();
        $this->assertTrue($this->entity->isActive());
    }
    
    public function testDeactivate()
    {
        $this->entity->deactivate();
        $this->assertFalse($this->entity->isActive());
    }
    
    public function testOptions()
    {
        $this->assertNotEmpty($this->entity->getOptions());
    }
}

class AmEntityMock extends CComponent
{
    public function getDefaultName()
    {
        return 'mock';
    }
    
    public function getConfigSection()
    {
        return 'components';
    }
    
    public function getFullClassName()
    {
        return 'appManager.tests.data.app.components.AmAppComponentMock';
    }
}