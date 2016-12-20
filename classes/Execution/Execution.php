<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

class Execution
{
	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var int
	 */
	protected $initiator_id;

	/**
	 * @var \ilObjUser | null
	 */
	protected $user;

	/**
	 * @var \ilDateTime
	 */
	protected $scheduled;

	/**
	 * @var \CaT\Plugin\AutomaticUserAdministration\Actions\UserAction
	 */
	protected $action;

	/**
	 * @var \ilDateTime | null
	 */
	protected $run_date;

	public function __construct($id, $initiator_id, \ilDateTime $scheduled, \CaT\Plugin\AutomaticUserAdministration\Actions\UserAction $action, \ilDateTime $run_date = null)
	{
		assert('is_int(id)');
		assert('is_int($initiator_id)');

		$this->id = $id;
		$this->initiator_id = $initiator_id;
		$this->scheduled = $scheduled;
		$this->action = $action;
		$this->run_date = $run_date;
	}

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the id of initiator
	 *
	 * @return int
	 */
	public function getInitatorId()
	{
		return $this->initiator_id;
	}

	/**
	 * Get user obj of initiator
	 *
	 * @return \ilObjUser
	 */
	public function getInitator()
	{
		if ($this->user === null) {
			$this->user = new \ilObjUser($this->initiator_id);
		}

		return $this->user;
	}

	/**
	 * Get scheduled
	 *
	 * @return \ilDateTime
	 */
	public function getScheduled()
	{
		return $this->scheduled;
	}

	/**
	 * Get the action object
	 *
	 * @return \CaT\Plugin\AutomaticUserAdministration\Actions\UserAction
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * Get the date of running
	 *
	 * @return \ilDateTime
	 */
	public function getRunDate()
	{
		return $this->run_date;
	}
}
