<?php
Yii::import('appManager.components.parser.property.AmPropertyOwn');
Yii::import('appManager.tests.components.parser.property.AmPropertyTestCase');

class AmPropertyOwnTest extends AmPropertyTestCase
{
    protected $name = 'property';
    
    public function testNotEmptyValue()
    {
        $this->assertEquals('string default', $this->getProperty('string')->getValue());
        $this->assertEquals(10, $this->getProperty('int')->getValue());
    }
    
     public function testShortDescription()
    {
        $description = 'Short description.';
        $this->assertEquals($description, $this->getProperty('shortDescription')->getDescription());
    }
    
    public function testLongDescription()
    {
        $description = "Short description.\nLong description.";
        $this->assertEquals($description, $this->getProperty('longDescription')->getDescription());
    }
    
    public function testVarDescription()
    {
        $description = "Var description.";
        $this->assertEquals($description, $this->getProperty('varDescription')->getDescription());
    }
    
    public function testPropertyDescription()
    {
        $description = "Property description.";
        $this->assertEquals($description, $this->getProperty('propertyDescription')->getDescription());
    }
    
    public function testFullDescription()
    {
        $description = "Short description.\nLong description.\nVar description.";
        $this->assertEquals($description, $this->getProperty('fullDescription')->getDescription());
    }
    
    public function testEmptyDescription()
    {
        $this->assertNull($this->getProperty('emptyDescription')->getDescription());
    }
    
    /**
     * @param string $name
     * @return AmPropertyOwn
     */
    protected function getProperty($name = null)
    {
        $name = $this->name . ($name ? ucfirst($name) : '');
        return new AmPropertyOwn($this->class, $this->class->getProperty($name));
    }
}
