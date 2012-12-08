<?php

Yii::import('appManager.components.AmConfig');

class AmConfigTest extends CTestCase
{   
    protected $config;
    protected $file;
    protected $source;
    
    public function setUp()
    {
        $source = AM_DATA_DIR . '/config.php';
        $file   = AM_DATA_DIR . '/config_tmp.php';
        $this->file = @copy($source, $file) ? $file : $source;
        $this->config = new AmConfig($this->file);
    }
    
    public function tearDown()
    {
        if ($this->file != AM_DATA_DIR . '/config.php') {
            @unlink($this->file);
        }
    }
    
    public function testGetLocation()
    {
        $this->assertEquals($this->file, $this->config->getLocation());
    }
    
    public function testIsWritable()
    {
        $this->assertSame(is_writable($this->file), $this->config->isWritable());
    }
    
    public function testSaveIfNotWritable()
    {
        $file = $this->file;
        if (!@chmod($file, 0400)) {
            $this->markTestSkipped('Permissions for the config file can\'t be changed.');
            return;
        }
        
        try {
            $this->config->save();
        } catch (CException $e) {
            chmod($file, 0775);
            return;
        } 
        chmod($file, 0775);
        $this->fail('Expected exception CException.');
    }
    
    public function testSaveIfWritable()
    {
        if (!@chmod($this->file, 0775)) {
            $this->markTestSkipped('Permissions for the config file can\'t be changed.');
            return;
        }
        $this->assertTrue($this->config->save());
    }
}
