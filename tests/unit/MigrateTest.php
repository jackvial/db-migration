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
        // Testing test includesit 
        $test_directory = 'test_includes/';
        $expected_result = 'Mtest_includes/drop_fruit.sqlMtest_includes/insert_fruit.sqlMtest_includes/update_fruit.sql';

        // Make sure a string is returned
        $this->assertTrue(is_string($this->migrate->gitDiff($test_directory)));

        // Strip all the hidden characters since we are only testing that the file names match
        $this->assertEquals(preg_replace('/[\n\r\s]+/', '', $this->migrate->gitDiff($test_directory)), $expected_result);  
    }

    public function testSplitStringReturnsArray()
    {
        $test_directory = 'test_includes/';
        $this->assertTrue(is_array($this->migrate->splitOnNewLine($this->migrate->gitDiff($test_directory)))); 
    }
    
    public function testMapFilePrefix()
    {
        $file_names = array('test_includes/8_test_query.sql', 
                            'test_includes/9_test_query.sql',
                            'test_includes/10_test_query.sql');


        $expected_result =  array(8 => 'test_includes/8_test_query.sql',
                                  9 => 'test_includes/9_test_query.sql',
                                  10 => 'test_includes/10_test_query.sql');

        $this->assertEquals($this->migrate->mapFilePrefix($file_names), $expected_result);
    }

    public function testSortByKey()
    {
        $input =  array(22 => 'includes/22_update_tables.sql',
                        11 => 'includes/11_insert_fruit.sql',
                        13 => 'includes/13_drop_columns.sql',
                        9 => 'includes/9_drop_zone.sql');

        $expected_result = array(9 => 'includes/9_drop_zone.sql',
                                11 => 'includes/11_insert_fruit.sql',
                                13 => 'includes/13_drop_columns.sql',
                                22 => 'includes/22_update_tables.sql',);

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

    public function testFilterByStatus()
    {
        $file_names = array('A     test_includes/8_test_query.sql', 
                            'A     test_includes/9_test_query.sql',
                            'D     test_includes/10_test_query.sql',
                            'M     test_includes/11_test_query.sql',
                            'M     test_includes/12_test_query.sql');

        $expected_result = array('A     test_includes/8_test_query.sql', 
                                'A     test_includes/9_test_query.sql');


        $this->assertEquals($this->migrate->filterByStatus('A', $file_names), $expected_result);
    }

    public function testStripStatus()
    {
        $file_names = array('A     test_includes/8_test_query.sql', 
                            'A     test_includes/9_test_query.sql');

        $expected_result = array('test_includes/8_test_query.sql', 
                                'test_includes/9_test_query.sql');


        $this->assertEquals($this->migrate->stripStatus($file_names), $expected_result);
    }

    public function testGetFirstCommitHashCode()
    {
        $file_name = 'test_includes/insert_fruit.sql';
        $expected_result = '1f56022dee94fbe7b041acfd38d77837e847fab5';

        $this->assertEquals($this->migrate->getFirstCommitHashCode($file_name), $expected_result);
    }

    public function testGetFileFirstCommitDateDropFruit()
    {
        $file_name = 'test_includes_fixed/drop_fruit.sql';
        $expected_result = '1422637962';

        $this->assertInternalType('string', $this->migrate->GetFileFirstCommitDate($file_name));
        $this->assertEquals($this->migrate->GetFileFirstCommitDate($file_name), $expected_result);
    }

    public function testGetFileFirstCommitDateInsertFruit()
    {
        $file_name = 'test_includes_fixed/insert_fruit.sql';
        $expected_result = '1422637654';

        $this->assertInternalType('string', $this->migrate->GetFileFirstCommitDate($file_name));
        $this->assertEquals($this->migrate->GetFileFirstCommitDate($file_name), $expected_result);
    }

    public function testGetFileFirstCommitDateUpdateFruit()
    {
        $file_name = 'test_includes_fixed/update_fruit.sql';
        $expected_result = '1422637962';

        $this->assertInternalType('string', $this->migrate->GetFileFirstCommitDate($file_name));
        $this->assertEquals($this->migrate->GetFileFirstCommitDate($file_name), $expected_result);
    }

    public function testMapTimeStampToKey()
    {

        $file_names = array('test_includes_fixed/drop_fruit.sql', 
                            'test_includes_fixed/insert_fruit.sql',
                            'test_includes_fixed/update_fruit.sql');

        $expected_result =  array(  14226379620 => 'test_includes_fixed/drop_fruit.sql',
                                    14226376541 => 'test_includes_fixed/insert_fruit.sql',
                                    14226379622 => 'test_includes_fixed/update_fruit.sql');

        $this->assertEquals($this->migrate->mapTimeStampToKey($file_names), $expected_result);
    }

}