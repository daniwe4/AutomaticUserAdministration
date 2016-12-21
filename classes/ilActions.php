<?php

namespace CaT\Plugins\AutomaticUserAdministration;

/**
 * Communication class between front- and backend.
 * E.g. GUI only use this class to get information from ILIAS DB.
 */
class ilActions
{
	const F_INDUCEMENT = "inducement";
	const F_LOGIN = "login";
	const F_ROLES = "roles";
	const F_SCHEDULED = "scheduled";

	/**
	 * @var \CaT\Plugins\AutomaticUserAdministration\Execution\DB
	 */
	protected $execution_db;

	public function __construct(\CaT\Plugins\AutomaticUserAdministration\Execution\DB $execution_db)
	{
		$this->execution_db = $execution_db;
	}

	/**
	 * Create a new execution
	 *
	 * @param int 			$initiator_id
	 * @param string 		$inducement
	 * @param \ilDateTime 	$scheduled
	 * @param \CaT\Plugins\AutomaticUserAdministration\Actions\Action 	$action
	 */
	public function createExecution(
		$initiator_id,
		$inducement,
		\ilDateTime $scheduled,
		\CaT\Plugins\AutomaticUserAdministration\Actions\Action $action
	) {
		$this->execution_db->create($initiator_id, $inducement, $scheduled, $action);
		die("sdsd");
	}

	public function getRoleAssignAction($login, array $roles)
	{
		assert('is_string($login)');

		require_once("Services/User/classes/class.ilObjUser.php");
		$user_id = \ilObjUser::_lookupId($login);
		$single_user_colection = new Collections\SingleUserCollection((int)$user_id);

		return new Actions\SetUserRoles($single_user_colection, $roles);
	}

	/**
	 * Get all openen executens
	 *
	 * @return AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getOpenExecutions()
	{
		$open_actions = $this->execution_db->getOpenExecutions();

		return $open_actions;
	}

	/**
	 * Get all openen executens
	 *
	 * @return AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getClosedExecutions()
	{
		$closed_actions = $this->execution_db->getClosedExecutions();

		return $closed_actions;
	}
}
