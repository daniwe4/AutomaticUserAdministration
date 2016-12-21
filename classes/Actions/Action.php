<?php

namespace CaT\Plugins\AutomaticUserAdministration\Actions;

/**
 * Interface for user actions
 */
interface Action extends \Serializable
{
	/**
	 * get the name of action
	 *
	 * @return string
	 */
	public function getName();

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
	 * Unserialize the collection object values
	 *
	 * @param string 	$data
	 *
	 * @return null
	 */
	public function unserialize($data);
}
