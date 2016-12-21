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
		return serialize($this->user_id);
	}

	/**
	 * @inheritdoc
	 */
	public function unserialize($data)
	{
		$user_id = unserialize($data);
		assert('is_int($user_id)');
		$this->user_id = $user_id;
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
