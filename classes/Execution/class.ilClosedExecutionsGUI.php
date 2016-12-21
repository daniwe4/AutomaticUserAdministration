<?php

use \CaT\Plugins\AutomaticUserAdministration\Execution;

class ilClosedExecutionsGUI
{
	const CMD_CLOSED_EXECUTIONS = "closedExecutions";

	/**
	 * @var \ilCtrl
	 */
	protected $gCtrl;

	/**
	 * @var \ilTemplate
	 */
	protected $gTpl;

	/**
	 * @var \CaT\Plugins\AutomaticUserAdministration\ilActions
	 */
	protected $actions;

	/**
	 * @var \ilAutomaticUserAdministrationr
	 */
	protected $parent_object;

	/**
	 * @var \ilAutomaticUserAdministrationPlugin
	 */
	protected $plugin_object;

	public function __construct(\ilAutomaticUserAdministrationConfigGUI $parent_object, \ilAutomaticUserAdministrationPlugin $plugin_object, \CaT\Plugins\AutomaticUserAdministration\ilActions $actions)
	{
		global $ilCtrl, $tpl;

		$this->gCtrl = $ilCtrl;
		$this->gTpl = $tpl;

		$this->parent_object = $parent_object;
		$this->plugin_object = $plugin_object;
		$this->actions = $actions;
	}

	public function executeCommand()
	{
		$cmd = $this->gCtrl->getCmd(self::CMD_CLOSED_EXECUTIONS);

		switch ($cmd) {
			case self::CMD_CLOSED_EXECUTIONS:
				$this->$cmd();
				break;
			default:
				throw new \Exception(__METHOD__.": unkown command ".$cmd);
		}
	}

	/**
	 * Show open actions
	 *
	 * @return null
	 */
	protected function closedExecutions()
	{
		$table = new Execution\ilClosedExecutionsTableGUI($this, $this->plugin_object, $this->actions);
		$table->determineOffsetAndOrder();
		$table->setData($this->actions->getClosedExecutions($table->getOrderField(), $table->getOrderDirection()));
		$this->gTpl->setContent($table->getHtml());
	}
}
