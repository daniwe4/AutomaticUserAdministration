<?php

use \CaT\Plugins\ilAutomaticUserAdministrationPlugin\ilActions;

require_once(__DIR__."/../vendor/autoload.php");
include_once("./Services/Repository/classes/class.ilCronHookPlugin.php");

/**
 * Plugin base class. Keeps all information the plugin needs
 */
class ilAutomaticUserAdministrationPlugin extends ilCronHookPlugin
{
	/**
	 * Get the name of the Plugin
	 *
	 * @return string
	 */
	public function getPluginName()
	{
		return "AutomaticUserAdministration";
	}

	public function getCronJobInstances()
	{
		require_once $this->getDirectory()."/classes/class.ilAutomaticUserAdministrationPlugin.php";
		$job = new ilAutomaticUserAdministrationPlugin();
		return array($job);
	}

	public function getCronJobInstance($a_job_id)
	{
		require_once $this->getDirectory()."/classes/class.ilAutomaticUserAdministrationPlugin.php";
		return new ilAutomaticUserAdministrationPlugin();
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
	 * @return ilEffAnalysisActions
	 */
	public function getActions()
	{
		if ($this->actions === null) {
			$this->actions = new ilActions();
		}

		return $this->actions;
	}
}
