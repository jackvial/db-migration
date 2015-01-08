<?php
require_once('migrate.php');
class MigrateTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // tests
    public function testInitMethod()
    {
        $Migrate = new Migrate();
        $this->assertTrue($Migrate->init() === 'is alive!');  
    }

    public function testGitDiff()
    {
        $Migrate = new Migrate();
        $this->assertTrue(is_string($Migrate->gitDiff()));  
        $this->assertEquals(trim($Migrate->gitDiff()), 'includes/update_tables.sql');  
    }

    public function testSplitStringReturnsArray()
    {
        $Migrate = new Migrate();
        $this->assertTrue(is_array($Migrate->splitOnNewLine($Migrate->gitDiff()))); 
    }

}
