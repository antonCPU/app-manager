<?php

Yii::import('appManager.components.AmConfig');

class AmConfigTest extends CTestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testNotExistedConfigLoad()
    {
        new AmConfig(AM_DATA_DIR . '/not_existed.php');
    }
    
    public function testExistedConfigLoad()
    {
        new AmConfig(AM_DATA_DIR . '/config.php');
    }
}
