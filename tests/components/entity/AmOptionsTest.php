<?php
Yii::import('appManager.components.entity.*');
Yii::import('appManager.components.parser.*');

class AmOptionsTest extends CTestCase
{
    protected $class;
    
    public function setUp()
    {
        Yii::import('appManager.vendors.*');
        require_once 'Zend/Reflection/Class.php';

        $this->class = new AmClassInfo(AM_DATA_DIR . '/AmClassInfoSource.php');
    }
    
    public function testOptionsCount()
    {
        $this->assertEquals(18, $this->getOptions()->getCount());
    }
    
    public function testOptionsValues()
    {
        $values = array(
            'propertyInt' => 20,
            'propertyMulti' => 'test',
            'methodString' => 'text',
        );
        $options = $this->getOptions($values)->get();
        foreach ($values as $name => $value) {
            $this->assertEquals($value, $options[$name]->getValue());
        }
    }
    
    public function testMagicGetter()
    {
        $this->assertEquals('10', $this->getOptions()->propertyInt);
    }
    
    public function testAttributesSetter()
    {
        $options = $this->getOptions();
        $options->attributes = array(
            'propertyInt'  => '20',
        );
        $this->assertEquals('20', $options->propertyInt);
        
        $properties = $options->get();
        $this->assertEquals(20, $properties['propertyInt']->getValue());
    }
    
    public function testUpdateConfig()
    {
        $options = $this->getOptions();
        $options->attributes = array(
            'propertyInt'  => '20',
        );
        $options->updateConfig();
        $config = $options->getConfig();
        $this->assertEquals(20, $config->itemAt('propertyInt'));
    }
            
    protected function getOptions($data = null)
    {
        return new AmOptions($this->class->getProperties(), new AmNode($data));
    }
}