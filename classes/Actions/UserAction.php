<?php

namespace CaT\Plugins\AutomaticUserAdministration\Actions;

/**
 * Interface for user actions
 */
interface UserAction extends \Serializable
{
	/**
	 * get the name of action
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Execute the action
	 *
	 * @return int[]
	 */
	public function run();

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
