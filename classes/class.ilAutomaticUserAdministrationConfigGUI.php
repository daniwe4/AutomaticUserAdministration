<?php
include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");

/**
 * Config gui to define auto admin exeutions
 *
 * @ilCtrl_Calls ilAutomaticUserAdministrationConfigGUI: ilOpenExecutionsGUI, ilClosedExecutionsGUI
 */
class ilAutomaticUserAdministrationConfigGUI extends ilPluginConfigGUI
{
	const CMD_OPEN_EXECUTIONS= "openExecutions";
	const CMD_CLOSED_EXECUTIONS = "closedExecutions";
	const CMD_CONFIGURE = "configure";

	/**
	 * @var \ilCtrl
	 */
	protected $gCtrl;

	/**
	 * @var \ilTabsGUI
	 */
	protected $gTabs;

	/**
	 * @var \CaT\Plugins\AutomaticUserAdministration\ilActions
	 */
	protected $actions;

	public function __construct()
	{
		global $ilCtrl, $ilTabs;
		$this->gCtrl = $ilCtrl;
		$this->gTabs = $ilTabs;
	}

	public function performCommand($cmd)
	{
		$this->setTabs();
		$this->actions = $this->plugin_object->getActions();

		$next_class = $this->gCtrl->getNextClass();

		switch ($next_class) {
			case "ilopenexecutionsgui":
				$this->forwardOpenExecutions();
				break;
			case "ilclosedexecutionsgui":
				$this->forwardClosedExecutions();
				break;
			default:
				switch ($cmd) {
					case self::CMD_CONFIGURE:
						$_GET["cmd"] = "view";
						// switch to cmd "view" to avoid function duplicates
					case self::CMD_OPEN_EXECUTIONS:
						$this->forwardOpenExecutions();
						break;
					case self::CMD_CLOSED_EXECUTIONS:
						$this->forwardClosedExecutions();
						break;
					default:
						throw new Exception("ilAutomaticUserAdministrationConfigGUI:: Unknown command: ".$cmd);
				}
		}
	}

	/**
	 * Forward to GUI dispalying open executions
	 *
	 * @return null
	 */
	protected function forwardOpenExecutions()
	{
		$this->gTabs->activateTab(self::CMD_OPEN_EXECUTIONS);
		require_once($this->plugin_object->getDirectory()."/classes/Execution/class.ilOpenExecutionsGUI.php");
		$gui = new \ilOpenExecutionsGUI($this, $this->plugin_object, $this->actions);
		$this->gCtrl->forwardCommand($gui);
	}

	/**
	 * Forward to GUI dispalying cloed executions
	 *
	 * @return null
	 */
	protected function forwardClosedExecutions()
	{
		$this->gTabs->activateTab(self::CMD_CLOSED_EXECUTIONS);
		require_once($this->plugin_object->getDirectory()."/classes/Execution/class.ilClosedExecutionsGUI.php");
		$gui = new \ilClosedExecutionsGUI($this, $this->plugin_object, $this->actions);
		$this->gCtrl->forwardCommand($gui);
	}

	/**
	 * Set the tabs
	 */
	protected function setTabs()
	{
		$open_link = $this->gCtrl->getLinkTarget($this, self::CMD_OPEN_EXECUTIONS);
		$closed_link = $this->gCtrl->getLinkTarget($this, self::CMD_CLOSED_EXECUTIONS);

		$this->gTabs->addTab(self::CMD_OPEN_EXECUTIONS, $this->plugin_object->txt("open_executions"), $open_link);
		$this->gTabs->addTab(self::CMD_CLOSED_EXECUTIONS, $this->plugin_object->txt("closed_executions"), $closed_link);
	}
}
