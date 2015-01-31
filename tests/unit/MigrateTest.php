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

    public function testGetNumberPrefix()
    {
        $this->markTestSkipped('must be revisited.');
        $file_path = 'includes/34_scripts.sql';

        $this->assertInternalType("int", $this->migrate->getNumberPrefix($file_path));
        $this->assertEquals($this->migrate->getNumberPrefix($file_path), 34);
    }

    public function testFilterByStatus()
    {
        $file_names = array('A     test_includes/8_test_query.sql', 
                            'A     test_includes/9_test_query.sql',
                            'D     test_includes/10_test_query.sql');

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
        $file_name = 'test_includes/drop_fruit.sql';
        $expected_result = '1422637962';

        $this->assertInternalType('string', $this->migrate->GetFileFirstCommitDate($file_name));
        $this->assertEquals($this->migrate->GetFileFirstCommitDate($file_name), $expected_result);
    }

    public function testGetFileFirstCommitDateInsertFruit()
    {
        $file_name = 'test_includes/insert_fruit.sql';
        $expected_result = '1422637654';

        $this->assertInternalType('string', $this->migrate->GetFileFirstCommitDate($file_name));
        $this->assertEquals($this->migrate->GetFileFirstCommitDate($file_name), $expected_result);
    }

    public function testGetFileFirstCommitDateUpdateFruit()
    {
        $file_name = 'test_includes/update_fruit.sql';
        $expected_result = '1422637962';

        $this->assertInternalType('string', $this->migrate->GetFileFirstCommitDate($file_name));
        $this->assertEquals($this->migrate->GetFileFirstCommitDate($file_name), $expected_result);
    }

    public function testMapTimeStampToKey()
    {

        $file_names = array('test_includes/drop_fruit.sql', 
                            'test_includes/insert_fruit.sql',
                            'test_includes/update_fruit.sql');

        $expected_result =  array(  14226379620 => 'test_includes/drop_fruit.sql',
                                    14226376541 => 'test_includes/insert_fruit.sql',
                                    14226379622 => 'test_includes/update_fruit.sql');

        $this->assertEquals($this->migrate->mapTimeStampToKey($file_names), $expected_result);
    }

}
