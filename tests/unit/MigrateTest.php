<?php
// Files path begins at root directory
require_once('migrate.php');
class MigrateTest extends \PHPUnit_Framework_TestCase
{
    // Contains the object handle    
    private $migrate;

    protected function setUp()
    {
        // Create a new instance before every test
        $this->migrate = new Migrate();
    }

    protected function tearDown()
    {
        // Delete the class instance
        unset($this->migrate);
    }

    public function testInitMethod()
    {
        $this->assertTrue($this->migrate->init() === 'is alive!');  
    }

    public function testGitDiff()
    {
        $this->assertTrue(is_string($this->migrate->gitDiff()));  
        $this->assertEquals(trim($this->migrate->gitDiff()), 'includes/update_tables.sql');  
    }

    public function testSplitStringReturnsArray()
    {
        $this->assertTrue(is_array($this->migrate->splitOnNewLine($this->migrate->gitDiff()))); 
    }
    
    public function testMapFileTimeStamps()
    {
        $file_names = array('includes/update_tables.sql', 'includes/insert_fruit.sql');
        $expected_result =  array('22:33:47 07/01/15' => 'includes/update_tables.sql',
                                  '19:18:12 07/01/15' => 'includes/insert_fruit.sql');
        $this->assertEquals($this->migrate->mapFileTimeStamps($file_names), $expected_result);
    }

}
