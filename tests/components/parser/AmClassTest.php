<?php
Yii::import('appManager.components.parser.AmClassInfo');
        
class AmClassInfoTest extends CTestCase
{    
    /**
     * @expectedException AmClassInfoException
     */
    public function testFileDoesNotExist()
    {
        $this->getClassInfo('not_existed_file.php');
    }
    
    /**
     * @expectedException AmClassInfoException
     */
    public function testFileHasNotClass()
    {
        $this->markTestSkipped('Should throw exception');
        
        $this->getClassInfo('config.php')->getDescription();
    }
    
    public function testFileName()
    {
        $this->assertEquals($this->getFileName(), $this->getClassInfo()->getFileName());
    }
    
    public function testClassName()
    {
        $this->assertEquals('TestClass', $this->getClassInfo()->getName());
    }
    
    protected function getFileName()
    {
        return AM_DATA_DIR . '/TestClass.php';
    }
    
    protected function getClassInfo($fileName = null)
    {
        $fileName = $fileName ? (AM_DATA_DIR . '/' . $fileName) : $this->getFileName();
        return new AmClassInfo($fileName);
    }
}
