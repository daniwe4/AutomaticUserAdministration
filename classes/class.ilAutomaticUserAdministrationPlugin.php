<?php
use CaT\Plugins\AutomaticUserAdministration;

require_once(__DIR__."/../vendor/autoload.php");
include_once("./Services/Cron/classes/class.ilCronHookPlugin.php");

/**
 * Plugin to autoadministrate user
 */
class ilAutomaticUserAdministrationPlugin extends ilCronHookPlugin
{
	/**
	 * @var AutomaticUserAdministration\ilActions
	 */
	protected $actions;

	/**
	 * @var AutomaticUserAdministration\Execution\ilDB
	 */
	protected $execution_db;

	/**
	 * Get the name of the Plugin
	 *
	 * @return string
	 */
	public function getPluginName()
	{
		return "Automatische Rollenzuweisung";
	}

	/**
	 * Get a number of job instance
	 *
	 * @return \ilAutomaticUserAdministrationJob[]
	 */
	public function getCronJobInstances()
	{
		require_once $this->getDirectory()."/classes/class.ilAutomaticUserAdministrationJob.php";
		$job = new \ilAutomaticUserAdministrationJob();
		return array($job);
	}

	/**
	 * Get a single job instance
	 *
	 * @return \ilAutomaticUserAdministrationJob[]
	 */
	public function getCronJobInstance($a_job_id)
	{
		require_once $this->getDirectory()."/classes/class.ilAutomaticUserAdministrationJob.php";
		return new \ilAutomaticUserAdministrationJob();
	}

	/**
	 * Get a closure to get txts from plugin.
	 *
	 * @return \Closure
	 */
	public function txtClosure()
	{
		return function ($code) {
			return $this->txt($code);
		};
	}

	/**
	 * Get the ilActions
	 *
	 * @return AutomaticUserAdministration\ilActions
	 */
	public function getActions()
	{
		if ($this->actions === null) {
			$this->actions = new AutomaticUserAdministration\ilActions($this->getExecutionDB());
		}

		return $this->actions;
	}

	protected function getExecutionDB()
	{
		global $ilDB;
		if ($this->execution_db === null) {
			$this->execution_db = new AutomaticUserAdministration\Execution\ilDB($ilDB);
		}

		return $this->execution_db;
	}
}
