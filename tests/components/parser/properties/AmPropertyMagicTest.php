<?php
Yii::import('appManager.components.parser.properties.AmPropertyMagic');
require_once dirname(__FILE__) . '/../AmClassPropertyTestCase.php';

class AmPropertyMagicTest extends AmClassPropertyTestCase
{
    protected $type = 'method';
    
    /**
     * @param string $name
     * @return AmPropertyOwn
     */
    protected function getProperty($name)
    {
        $name = 'set' . ucfirst($name);
        return new AmPropertyMagic($this->class, $this->class->getMethod($name));
    }
}