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
        $migrate = new Migrate(); 
        $this->assertTrue($migrate->init() === 'is alive!');  
    }

}
