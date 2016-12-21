<?php
/* Copyright (c) 1998-2015 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once "Services/Cron/classes/class.ilCronJob.php";

/**
* Implementation of the cron job
*/
class ilAutomaticUserAdministrationJob extends ilCronJob
{

	public function __construct()
	{
		$this->plugin =
			ilPlugin::getPluginObject(
				IL_COMP_SERVICE,
				"Cron",
				"crnhk",
				ilPlugin::lookupNameForId(IL_COMP_SERVICE, "Cron", "crnhk", $this->getId())
			);

		$this->actions = $this->plugin->getActions();
	}

	public function getId()
	{
		return "autouseradmin";
	}

	/**
	 * Implementation of abstract function from ilCronJob
	 * @return	string
	 */
	public function getTitle()
	{
		return $this->plugin->txt("title");
	}

	public function getDescription()
	{
		return $this->plugin->txt("description");
	}

	/**
	 * Is to be activated on "installation"
	 *
	 * @return boolean
	 */
	public function hasAutoActivation()
	{
		return false;
	}

	/**
	 * Can the schedule be configured?
	 *
	 * @return boolean
	 */
	public function hasFlexibleSchedule()
	{
		return false;
	}

	/**
	 * Get schedule type
	 *
	 * @return int
	 */
	public function getDefaultScheduleType()
	{
		return ilCronJob::SCHEDULE_TYPE_DAILY;
	}

	/**
	 * Get schedule value
	 *
	 * @return int|array
	 */
	public function getDefaultScheduleValue()
	{
		return 1;
	}

	/**
	 * Get called if the cronjob is started
	 * Executing the ToDo's of the cronjob
	 */
	public function run()
	{
		$now = date("Y-m-d H:i:s");
		$cron_result = new ilCronJobResult();

		foreach ($this->actions->getOpenExecutionsScheduledFor($now) as $execution) {
			$execution->getActions()->run();
			$this->actions->setExecutionRunned($execution->getId());
			ilCronManager::ping($this->getId());
		}

		$cron_result->setStatus(ilCronJobResult::STATUS_OK);
		return $cron_result;
	}
}
