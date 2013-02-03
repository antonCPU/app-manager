<?php

abstract class AmPropertyTestCase extends CTestCase
{
    protected $class;
    protected $name;
    
    public function setUp()
    {
        Yii::import('appManager.vendors.*');
        require_once 'Zend/Reflection/Class.php';
        $className = 'AmClassInfoSource';
        include_once AM_DATA_DIR . '/' . $className . '.php';
        
        $this->class = new Zend_Reflection_Class($className);
    }
    
    public function testName()
    {
        $property = $this->getProperty();
        $this->assertEquals($this->name, $property->getName());
    }
   
    public function testEmptyValue()
    {
        $this->assertEmpty($this->getProperty()->getValue());
    }
     
    public function testEmptyType()
    {
        $this->assertEmpty($this->getProperty()->getType());
    }
    
    public function testSingleType()
    {
        $this->assertEquals(AmProperty::TYPE_STRING, $this->getProperty('string')->getType());
    }
   
    public function testMixedIfSeveralTypes()
    {
        $this->assertEquals(AmProperty::TYPE_MIXED, $this->getProperty('multi')->getType());
    }
    
    public function testTypes()
    {
        $types = array(
            AmProperty::TYPE_STRING, 
            AmProperty::TYPE_BOOLEAN,
            AmProperty::TYPE_INTEGER,
        );
        $this->assertEquals($types, $this->getProperty('multi')->getTypes());
    }
    
    /**
     * @param string $name
     * @return AmClassProperty
     */
    abstract protected function getProperty($name);
}