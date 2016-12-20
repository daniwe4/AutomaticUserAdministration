<?php

namespace CaT\Plugins\AutomaticUserAdministration\Collections;

class SingleUserCollection implements UserCollection
{
	/**
	 * @var int
	 */
	protected $user_id;

	/**
	 * Get all user from collection
	 *
	 * @return int[]
	 */
	public function getUsers()
	{
		return array($this->user_id);
	}

	/**
	 * Seralize the collection object values
	 *
	 * @return string
	 */
	public function serialize()
	{
		return serialize($this->getUsers());
	}

	/**
	 * Deserialize the collection object values
	 *
	 * @return null
	 */
	public function deserialize($data)
	{
		$data = unserialize($data);

		$this->user_id = $data[0];
	}
}
