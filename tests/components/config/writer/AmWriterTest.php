<?php
Yii::import('appManager.components.config.AmConfig');
Yii::import('appManager.components.config.writer.AmWriter');

class AmWriterTest extends CTestCase
{
    protected $writer;
    
    public function setUp()
    {
        $this->writer = new AmWriter(new AmConfig($this->getLocation()));
    }
    
    public function testVersion()
    {
        $matches = array();
        preg_match('/@version (.+)/', $this->writer->getContent(), $matches);
        $this->assertEquals($matches[1], date('Y-m-d H:i:s'));
    }
    
    public function testEqual()
    {
        $content = file_get_contents($this->getLocation());
        $content = preg_replace('/@version .+/', '@version ' . date('Y-m-d H:i:s'), $content);
        $this->assertEquals($content, $this->writer->getContent());
    }
    
    protected function getLocation()
    {
        return AM_DATA_DIR . '/config_generated.php';
    }
}