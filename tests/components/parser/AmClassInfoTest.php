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
        $this->assertEquals('AmClassInfoSource', $this->getClassInfo()->getName());
    }
    
    public function testDescription()
    {
        $this->assertEquals("Long Description.\nWith line breaks.", $this->getClassInfo()->getDescription());
    }
    
    public function testSummary()
    {
        $this->assertEquals("Short Description", $this->getClassInfo()->getSummary());
    }
    
    public function testAuthor()
    {
        $this->assertEquals('Author Name <author@email.com>', $this->getClassInfo()->getAuthor());
    }
    
    public function testLink()
    {
        $this->assertEquals('http://link-to-page', $this->getClassInfo()->getLink());
    }
    
    public function testVersion()
    {
        $this->assertEquals('0.7', $this->getClassInfo()->getVersion());
    }
    
    public function testIsSubclassOf()
    {
        $info = $this->getClassInfo();
        $this->assertFalse($info->isSubclassOf('CTestCase'));
        $this->assertTrue($info->isSubclassOf('CComponent'));
    }
    
    protected function getFileName()
    {
        return AM_DATA_DIR . '/AmClassInfoSource.php';
    }
    
    /**
     * @param string $fileName
     * @return AmClassInfo
     */
    protected function getClassInfo($fileName = null)
    {
        $fileName = $fileName ? (AM_DATA_DIR . '/' . $fileName) : $this->getFileName();
        return new AmClassInfo($fileName);
    }
}
