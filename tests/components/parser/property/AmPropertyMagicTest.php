<?php
Yii::import('appManager.components.parser.property.AmPropertyMagic');
Yii::import('appManager.tests.components.parser.property.AmPropertyTestCase');

class AmPropertyMagicTest extends AmPropertyTestCase
{
    protected $name = 'method';
    
    public function testShortDescription()
    {
        $description = 'Short description';
        $this->assertEquals($description, $this->getProperty('shortDescription')->getDescription());
    }
    
    public function testLongDescription()
    {
        $description = "Full description.";
        $this->assertEquals($description, $this->getProperty('longDescription')->getDescription());
    }
    
    public function testParamDescription()
    {
        $description = "Param description.";
        $this->assertEquals($description, $this->getProperty('paramDescription')->getDescription());
    }
    
    public function testFullDescription()
    {
        $description = "Full description.\nParam description.";
        $this->assertEquals($description, $this->getProperty('fullDescription')->getDescription());
    }
    
    public function testEmptyDescription()
    {
        $this->assertNull($this->getProperty('emptyDescription')->getDescription());
    }
            
    /**
     * @param string $name
     * @return AmPropertyMagic
     */
    protected function getProperty($name = null)
    {
        $name = 'setMethod' . ($name ? ucfirst($name) : '');
        return new AmPropertyMagic($this->class, $this->class->getMethod($name));
    }
}