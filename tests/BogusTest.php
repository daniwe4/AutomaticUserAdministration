<?php

/**
 * Sample for PHP Unit tests
 */
class BogusTest extends PHPUnit_Framework_TestCase
{

	public function testSuccessfull()
	{
		$test_var = "Peter";

		$this->assertEquals("Peter", $test_var);
	}

	public function testFailed()
	{
		try {
			$this->checkValue("Bernd");
			$this->assertFalse("Should have raised.");
		} catch (Exception $e) {
		}
	}

	protected function checkValue($value)
	{
		if ($value != "Peter") {
			throw new Exception("Value is wrong");
		}
	}
}
