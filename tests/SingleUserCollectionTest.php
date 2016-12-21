<?php

use \CaT\Plugins\AutomaticUserAdministration\Collections\SingleUserCollection;
use PHPUnit\Framework\TestCase;

class SingleUserCollectionTest extends TestCase
{
	public function test_serialize()
	{
		$collection = new SingleUserCollection(10);
		$this->assertEquals(array(10), $collection->getUsers());

		$seralized_collection = serialize($collection);
		$this->assertInternalType('string', $seralized_collection);

		return array($seralized_collection, $collection);
	}

	/**
	 * @depends test_serialize
	 */
	public function test_unseralize($args)
	{
		$collection = $args[1];
		$unserialized_collection = unserialize($args[0]);

		$this->assertEquals($collection, $unserialized_collection);
		$this->assertEquals(array(10), $unserialized_collection->getUsers());
	}
}
