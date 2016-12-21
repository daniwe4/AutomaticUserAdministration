<?php

namespace CaT\Plugins\AutomaticUserAdministration\Actions;

/**
 * Interface for user actions
 */
class SetUserRoles implements UserAction
{
	/**
	 * @var \CaT\Plugins\AutomaticUserAdministration\UserCollection
	 */
	protected $user_collection;

	/**
	 * @var int[]
	 */
	protected $roles;

	public function __construct(\CaT\Plugins\AutomaticUserAdministration\UserCollection $user_collection, array $roles)
	{
		$this->user_collection = $user_collection;
		$this->roles = $roles;
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return "set_user_role";
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
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
	public function deserialize($data)
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
}
