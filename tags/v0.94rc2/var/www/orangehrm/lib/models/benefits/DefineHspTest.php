<?php
// Call DefineHspTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'DefineHspTest::main');
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'DefineHsp.php';

require_once ROOT_PATH."/lib/confs/Conf.php";
require_once ROOT_PATH."/lib/common/UniqueIDGenerator.php";

/**
 * Test class for DefineHsp.
 * Generated by PHPUnit on 2008-02-18 at 11:42:04.
 */
class DefineHspTest extends PHPUnit_Framework_TestCase {
	private $oldValues;
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('DefineHspTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    	$conf = new Conf();
    	$this->connection = mysql_connect($conf->dbhost.":".$conf->dbport, $conf->dbuser, $conf->dbpass);
        mysql_select_db($conf->dbname);
        
        $result = mysql_query("SELECT * FROM hs_hr_config;");
        $this->oldValues = array();
        
        while($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$this->oldValues[] = $row;         	
        }
        
        mysql_free_result($result);
        
		$this->assertTrue(mysql_query("TRUNCATE `hs_hr_config`;", $this->connection), mysql_error());
		$this->assertTrue(mysql_query("INSERT INTO hs_hr_config (`key`, `value`) VALUES('hsp_current_plan', '0')"), mysql_error());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    	$this->assertTrue(mysql_query("TRUNCATE `hs_hr_config`;", $this->connection), mysql_error());
    	
    	foreach ($this->oldValues as $row) {
    		$query = "INSERT INTO `hs_hr_config` VALUES ('" . implode("', '", $row) . "')";
    		$this->assertTrue(mysql_query($query));
    	}

    	UniqueIDGenerator::getInstance()->resetIDs();
    }

    public function testfetchHsp() {
		$definedHsp = DefineHsp::fetchHsp();
		$expected = '0';

		$this->assertEquals($expected, $definedHsp);
    }
}

// Call DefineHspTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'DefineHspTest::main') {
    DefineHspTest::main();
}
?>