<?php
require_once('migrate.php');
class MigrateTest extends \PHPUnit_Framework_TestCase
{
    // Contains the object handle    
    var $migrate;

    protected function setUp()
    {
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

}
