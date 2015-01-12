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

    public function testGitDiff()
    {
        $this->markTestSkipped('must be revisited.');
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

        /*
        $expected_result =  array('22:33:47 07/01/15' => 'includes/update_tables.sql',
                                  '19:18:12 07/01/15' => 'includes/insert_fruit.sql');
        */
        $expected_result =  array(150107223347 => 'includes/update_tables.sql',
                                  150107191812 => 'includes/insert_fruit.sql');

        $this->assertEquals($this->migrate->mapFileTimeStamps($file_names), $expected_result);
    }

    public function testSortByKey()
    {
        $input =  array(151109231433 => 'includes/update_tables.sql',
                        131109111343 => 'includes/insert_fruit.sql',
                        150701121812 => 'includes/drop_columns.sql',
                        120906230101 => 'includes/drop_zone.sql');

        $expected_result = array(120906230101 => 'includes/drop_zone.sql',
                                131109111343 => 'includes/insert_fruit.sql',
                                150701121812 => 'includes/drop_columns.sql',
                                151109231433 => 'includes/update_tables.sql',);

        $this->assertTrue(is_array($this->migrate->sortByKey($input)));
        $this->assertSame($this->migrate->sortByKey($input), $expected_result);
    }

    public function testSortByKeySimple()
    {
        $input =  array(1234567 => "includes/update_tables.sql",
                        67788998 => "includes/insert_fruit.sql",
                        1234 => "includes/drop_columns.sql");

        function isAssoc($arr)
        {
            return array_keys($arr) !== range(0, count($arr) - 1);
        }

        $this->assertTrue(isAssoc($this->migrate->sortByKey($input)));
    }

}
