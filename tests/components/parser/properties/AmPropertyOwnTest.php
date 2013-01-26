<?php
Yii::import('appManager.components.parser.properties.AmPropertyOwn');
require_once dirname(__FILE__) . '/../AmClassPropertyTestCase.php';

class AmPropertyOwnTest extends AmClassPropertyTestCase
{
    public $type = 'property';
    
    public function testNotEmptyValue()
    {
        $this->assertEquals('string default', $this->getProperty('propertyString')->getValue());
        $this->assertEquals(10, $this->getProperty('propertyInt')->getValue());
    }
    
    /**
     * @param string $name
     * @return AmPropertyOwn
     */
    protected function getProperty($name)
    {
        return new AmPropertyOwn($this->class, $this->class->getProperty($name));
    }
}
