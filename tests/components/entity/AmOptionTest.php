<?php
Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.parser.*');

class AmOptionTest extends CTestCase
{
    protected $class;
    
    public function setUp()
    {
        Yii::import('appManager.vendors.*');
        require_once 'Zend/Reflection/Class.php';

        $this->class = new Zend_Reflection_Class('AmOptionClassMock');
    }
    
    public function testName()
    {
        $this->assertEquals('property', $this->getOption('property')->getName());
    }
    
    public function testDefaultValue()
    {
        $this->assertEquals('test value', $this->getOption('propertyValue')->getDefault());
    }
    
    public function testValue()
    {
        $this->assertNull($this->getOption('property')->getValue());
    }
    
    public function testSetValue()
    {
        $option = $this->getOption('property');
        $option->setValue('test');
        $this->assertEquals('test', $option->getValue());
    }
    
    public function testDescription()
    {
        $this->assertEquals('Var description.', $this->getOption('property')->getDesc());
    }
    
    public function testNullValue()
    {
        $this->assertEquals('null', $this->getOption('propertyNull')->getTextValue());
    }
    
    public function testIntValue()
    {
        $this->assertEquals('123', $this->getOption('propertyInt')->getTextValue());
    }
    
    public function testTextValue()
    {
        $this->assertEquals("'text'", $this->getOption('propertyText')->getTextValue());
    }
    
    public function testArrayValue()
    {
        $this->assertEquals('array()', $this->getOption('propertyArray')->getTextValue());
    }
    
    public function testSetTextValue()
    {
        $option = $this->getOption('propertyInt');
        $option->setTextValue('20');
        $this->assertEquals('20', $option->getTextValue());
    }
    
    public function testSetTextValueToInt()
    {
        $option = $this->getOption('propertyInt');
        $option->setTextValue('20');
        $this->assertEquals(20, $option->getValue());
    }
    
    protected function getOption($name)
    {
        $property = AmPropertyFactory::create($this->class, $this->class->getProperty($name));
        return new AmOption($property);
    }
            
}

class AmOptionClassMock
{
    /**
     * @var int|string var description.
     * @since 1.0
     */
    public $property;
    
    public $propertyValue = 'test value';
    public $propertyNull = null;
    public $propertyInt = 123;
    public $propertyText = 'text';
    public $propertyArray = array();
}