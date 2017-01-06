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
	const F_EXECUTION_ID = "executionId";

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
	}

	/**
	 * Update an existing execution
	 *
	 * @param int 			$execution_id
	 * @param int 			$initiator_id
	 * @param string 		$inducement
	 * @param \ilDateTime 	$scheduled
	 * @param \CaT\Plugins\AutomaticUserAdministration\Actions\Action 	$action
	 */
	public function updateExecution(
		$execution_id,
		$initiator_id,
		$inducement,
		\ilDateTime $scheduled,
		\CaT\Plugins\AutomaticUserAdministration\Actions\Action $action
	) {
		$execution = $this->getExecutionById($execution_id);
		$execution = $execution->withInitiator($initiator_id)
							   ->withInducement($inducement)
							   ->withAction($action)
							   ->withScheduled($scheduled);

		$this->execution_db->update($execution);
	}

	/**
	 * Delete execution
	 *
	 * @param int 		$execution_id
	 */
	public function deleteExecutionById($execution_id)
	{
		$this->execution_db->delete($execution_id);
	}

	/**
	 * Get execution
	 *
	 * @param int 		$execution_id
	 */
	public function getExecutionById($execution_id)
	{
		return $this->execution_db->getExecution($execution_id);
	}

	/**
	 * Get form values for executen
	 *
	 * @param int 		$execution_id
	 *
	 * @return array<string, mixed>
	 */
	public function getExecutionValues($execution_id)
	{
		$execution = $this->getExecutionById($execution_id);

		$users = $execution->getAction()->getUserCollection()->getUsers();
		$user = new \ilObjUser($users[0]);

		$scheduled = $execution->getScheduled()->get(IL_CAL_DATETIME);
		$scheduled = explode(" ", $scheduled);
		$datetime["date"] = $scheduled[0];
		$datetime["time"] = $scheduled[1];

		$values = array();
		$values[self::F_INDUCEMENT] = $execution->getInducement();
		$values[self::F_LOGIN] = $user->getLogin();
		$values[self::F_ROLES] = $execution->getAction()->getRoles();
		$values[self::F_SCHEDULED] = $datetime;
		$values[self::F_EXECUTION_ID] = $execution->getId();

		return $values;
	}

	/**
	 * Set execution runned
	 *
	 * @param int 		$execution_id
	 */
	public function setExecutionRunned($execution_id)
	{
		$execution = $this->getExecutionById($execution_id);

		$execution = $execution->withRunDate(new \ilDateTime(date("Y-m-d H:i:s"), IL_CAL_DATETIME));

		$this->execution_db->update($execution);
	}

	/**
	 * Get action object for SetUserRoles
	 *
	 * @param string 	$login
	 * @param int[] 	$roles
	 *
	 * @return Actions\SetUserRoles
	 */
	public function getSetUserRolesAction($login, array $roles)
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
	 * @param string 	$order_column
	 * @param string 	$order_sirection
	 *
	 * @return AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getOpenExecutions($order_column, $order_direction)
	{
		$open_actions = $this->execution_db->getOpenExecutions($order_column, $order_direction);

		return $open_actions;
	}

	/**
	 * Get open executens scheduled for $date
	 *
	 * @param string 	$date
	 *
	 * @return AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getOpenExecutionsScheduledFor($date)
	{
		return $this->execution_db->getOpenExecutionsScheduledFor($date);
	}

	/**
	 * Get all openen executens
	 *
	 * @param string 	$order_column
	 * @param string 	$order_sirection
	 *
	 * @return AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getClosedExecutions($order_column, $order_direction)
	{
		$closed_actions = $this->execution_db->getClosedExecutions($order_column, $order_direction);

		return $closed_actions;
	}

	/**
	 * Get names for role ids
	 *
	 * @param int[]
	 *
	 * @return string[]
	 */
	public function getNameForRoles(array $roles)
	{
		$ret = array();
		foreach ($roles as $role_id) {
			$ret[] = \ilObject::_lookupTitle($role_id);
		}

		return $ret;
	}
}
