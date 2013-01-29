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
