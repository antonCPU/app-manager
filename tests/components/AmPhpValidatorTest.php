<?php

Yii::import('appManager.components.AmPhpValidator');

class AmPhpValidatorTest extends CTestCase
{
    public $validator;
    public $model;
    
    public function setUp()
    {
        $this->validator = new AmPhpValidator;
        $this->validator->attributes = array('foo');
        
        $this->model = new ModelMock;
    }
    
    /**
     * @dataProvider provider
     */
    public function testValidate($value, $isCorrect)
    {
        $this->model->foo = $value;
        
        $this->validator->validate($this->model);
        $this->assertSame($isCorrect, !$this->model->hasErrors(), $value);
    }
    
    public function provider()
    {
        return array(
            array('invalid text', false),
            array('"valid text"', true),
            array("'valid text'", true),
            array('23'          , true),
            array('23.3'        , true),
            array('null'        , true),
            array('array()'     , true),
            array('array('      , false),
            array('array("test"=>"ok")', true),
        );
    }
    
}

class ModelMock extends CFormModel
{
    public $foo;
}