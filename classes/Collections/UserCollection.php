<?php

namespace CaT\Plugins\AutomaticUserAdministration\Collections;

/**
 * Interface for user collections
 */
interface UserCollection extends \Serializable
{
	/**
	 * Get all user from collection
	 *
	 * @return int[]
	 */
	public function getUsers();

	/**
	 * Seralize the collection object values
	 *
	 * @return string
	 */
	public function serialize();

	/**
	 * Deserialize the collection object values
	 *
	 * @param string 	$data
	 *
	 * @return null
	 */
	public function deserialize($data);
}
