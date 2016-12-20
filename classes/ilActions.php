<?php

namespace CaT\Plugins\AutomaticUserAdministration;

/**
 * Communication class between front- and backend.
 * E.g. GUI only use this class to get information from ILIAS DB.
 */
class ilActions
{
	const F_ACTION = "action";
	const F_LOGIN = "login";
	const F_ROLES = "roles";
	const F_SCHEDULED = "scheduled";

	public function getOpenActions()
	{
		$ret = array();

		$ret["id"] = 1;
		$ret["scheduled"] = date("d.m.Y H:i:s");
		$ret["action"] = "Test";
		$ret["login"] = "Test";
		$ret["firstname"] = "Test";
		$ret["lastname"] = "Test";
		$ret["roles"] = "Test";
		$ret["initiator"] = "Test";

		return array($ret);
	}
}
