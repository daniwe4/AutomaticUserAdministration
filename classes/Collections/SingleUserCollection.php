<?php

namespace CaT\Plugins\AutomaticUserAdministration\Collections;

class SingleUserCollection implements UserCollection
{
	/**
	 * @var int
	 */
	protected $user_id;

	public function __construct($user_id)
	{
		assert('is_int($user_id)');
		$this->user_id = $user_id;
	}

	/**
	 * @inheritdoc
	 */
	public function getUsers()
	{
		return array($this->user_id);
	}

	/**
	 * @inheritdoc
	 */
	public function serialize()
	{
		return serialize($this->getUsers());
	}

	/**
	 * @inheritdoc
	 */
	public function deserialize($data)
	{
		$data = unserialize($data);

		$this->user_id = $data[0];
	}

	/**
	 * Get new instance with user id
	 *
	 * @param type $user_id
	 *
	 * @return Collections\SingleUserCollection
	 */
	public function withUserId($user_id)
	{
		assert('is_int($user_id)');
		$clone = clone $this;
		$clone->user_id = $user_id;
		return $clone;
	}
}
