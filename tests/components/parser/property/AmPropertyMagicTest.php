<?php
Yii::import('appManager.components.parser.property.AmPropertyMagic');
Yii::import('appManager.tests.components.parser.property.AmPropertyTestCase');

class AmPropertyMagicTest extends AmPropertyTestCase
{
    protected $name = 'method';
    
    public function testDescriptionInMainSection()
    {
        $description = 'Top level description';
        $this->assertEquals($description, $this->getProperty('topDescription')->getDescription());
    }
    
    public function testFullDescription()
    {
        $description = 'Top level description. Param description';
        $this->assertEquals($description, $this->getProperty('fullDescription')->getDescription());
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