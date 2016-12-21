<?php

namespace CaT\Plugins\AutomaticUserAdministration\Actions;

abstract class UserAction implements Action
{
	/**
	 * @var \CaT\Plugins\AutomaticUserAdministration\UserCollection
	 */
	protected $user_collection;

	public function __construct(\CaT\Plugins\AutomaticUserAdministration\Collections\UserCollection $user_collection)
	{
		$this->user_collection = $user_collection;
	}
}
