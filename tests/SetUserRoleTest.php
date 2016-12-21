<?php

use \CaT\Plugins\AutomaticUserAdministration\Actions\SetUserRoles;
use \CaT\Plugins\AutomaticUserAdministration\Collections\SingleUserCollection;
use PHPUnit\Framework\TestCase;

class SetUserRoleTest extends TestCase
{

	public function setUp()
	{
		$this->user_collection = $this->createMock("\\CaT\\Plugins\\AutomaticUserAdministration\\Collections\\SingleUserCollection");

		$this->user_collection->method("serialize")
							  ->willReturn(serialize(10));

		$this->user_collection->method("unserialize");

		$this->roles = array(2,3,4,5);
	}

	public function testSerialize()
	{
		$action = new SetUserRoles($this->user_collection, $this->roles);
		$serialized_action = serialize($action);

		$this->assertInternalType('string', $serialized_action);

		return array($serialized_action, $action);
	}

	/**
	 * @depends test_serialize
	 */
	public function testUnseralize($args)
	{
		$action = $args[1];
		$unserialized_action = unserialize($args[0]);

		$this->assertEquals($action, $unserialized_action);
	}
}
