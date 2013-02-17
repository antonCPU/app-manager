<?php
Yii::import('appManager.models.AmEntityComponents');

class AmEntityCoreComponentsTest extends CTestCase
{
    protected $entity;
    
    public function setUp()
    {
        $this->entity = new AmEntityCoreComponents('system.components');
    }
    
    public function testChildrenCount()
    {
        $this->assertCount(16, $this->entity->getChildren());
    }
    
    public function testChildId()
    {
        $this->assertEquals('system.components.db.CDbConnection', $this->getChild()->getId());
    }
    
    public function testChildName()
    {
        $this->assertEquals('db', $this->getChild()->getName());
    }
    
    public function testChildClassName()
    {
        $this->assertEquals('CDbConnection', $this->getChild()->getClassName());
    }
    
    public function testChildFullClassName()
    {
        $this->assertEquals('system.db.CDbConnection', $this->getChild()->getFullClassName());
    }
    
    protected function getChild()
    {
        return $this->entity->getChild('db.CDbConnection');
    }
}