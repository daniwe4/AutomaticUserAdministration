<?php

namespace CaT\Plugins\AutomaticUserAdministration\Actions;

/**
 * Interface for user actions
 */
class SetUserRoles extends UserAction
{
	/**
	 * @var int[]
	 */
	protected $roles;

	/**
	 * @var \ilRbacReview
	 */
	protected $g_rbacreview;

	/**
	 * @var \ilRbacAdmin
	 */
	protected $g_rbacadmin;

	public function __construct(
		\CaT\Plugins\AutomaticUserAdministration\Collections\UserCollection $user_collection,
		array $roles
	) {
		parent::__construct($user_collection);
		$this->roles = $roles;
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return "set_user_role";
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		global $rbacadmin, $rbacreview;
		$this->g_rbacadmin = $rbacadmin;
		$this->g_rbacreview = $rbacreview;

		$user_assigned_to = $this->getUserGlobalRoles();

		$new_roles = array_diff($this->roles, $user_assigned_to);
		$deprecated_roles = array_diff($user_assigned_to, $this->roles);

		$this->deassignRoles($deprecated_roles);
		$this->assignRoles($new_roles);
	}

	/**
	 * Get roles user is assigned to
	 *
	 * @return int[]
	 */
	protected function getUserGlobalRoles()
	{
		return $this->g_rbacreview->assignedGlobalRoles($this->user_id);
	}

	/**
	 * Deassign user from roles
	 *
	 * @param int[]
	 */
	protected function deassignRoles(array $deprecated_roles)
	{
		foreach ($deprecated_roles as $role_id) {
			$this->g_rbacadmin->deassignUser($role_id, $this->user_id);
		}
	}

	/**
	 * Assign user to roles
	 *
	 * @param int[]
	 */
	protected function assignRoles(array $new_roles)
	{
		foreach ($deprecated_roles as $role_id) {
			$this->g_rbacadmin->assignUser($role_id, $this->user_id);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function serialize()
	{
		return serialize(["collection" => $this->user_collection
							,"roles" => $this->roles
						 ]);
	}

	/**
	 * @inheritdoc
	 */
	public function unserialize($data)
	{
		$data = unserialize($data);
		$this->user_collection = $data["collection"];
		$this->roles = $data["roles"];
	}

	/**
	 * Get new intance with user collection
	 *
	 * @param \CaT\Plugins\AutomaticUserAdministration\UserCollection $user_collection
	 *
	 * @return Actions\SetUserRoles
	 */
	public function withUserCollection(\CaT\Plugins\AutomaticUserAdministration\UserCollection $user_collection)
	{
		$clone = clone $this;
		$clone->user_collection = $user_collection;
		return $clone;
	}

	/**
	 * Get new instance with roles
	 *
	 * @param array $roles
	 *
	 * @return Actions\SetUserRoles
	 */
	public function withRoles(array $roles)
	{
		$clone = clone $this;
		$clone->roles = $roles;
		return $clone;
	}

	/**
	 * Get the user collection
	 *
	 * @return \CaT\Plugins\AutomaticUserAdministration\UserCollection
	 */
	public function getUserCollection()
	{
		return $this->user_collection;
	}

	/**
	 * Get the roles
	 *
	 * @return int[]
	 */
	public function getRoles()
	{
		return $this->roles;
	}
}
