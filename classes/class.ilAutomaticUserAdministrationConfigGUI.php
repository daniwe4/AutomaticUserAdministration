<?php

use \CaT\Plugins\AutomaticUserAdministration\Open;
use \CaT\Plugins\AutomaticUserAdministration\Closed;

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");

/**
 * Config gui to define auto admin actions
 *
 * @ilCtrl_Calls ilAutomaticUserAdministrationConfigGUI: ilOpenActionsTableGUI, ilClosedActionsTableGUI
 */
class ilAutomaticUserAdministrationConfigGUI extends ilPluginConfigGUI
{
	const CMD_OPEN_ACTIONS = "openActions";
	const CMD_CLOSED_ACTIONS = "closedActions";

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
			case "ilopenactionstablegui":
				$this->forwardOpenActions();
				break;
			case "ilclosedactionstablegui":
				$this->forwardClosedActions();
				break;
			default:
				switch ($cmd) {
					case self::CMD_OPEN_ACTIONS:
						$this->forwardOpenActions();
					    break;
					case self::CMD_CLOSED_ACTIONS:
						$this->forwardClosedActions();
					    break;
					default:
					    throw new Exception("ilAutomaticUserAdministrationConfigGUI:: Unknown command: ".$cmd);
				}
		}
	}

	/**
	 * Forward to GUI dispalying open actions
	 *
	 * @return null
	 */
	protected function forwardOpenActions()
	{
		$this->gTabs->activateTab(self::CMD_OPEN_ACTIONS);
		$gui = new Open\ilOpenActionsTableGUI($this, $this->plugin_object, $this->actions);
		$this->gCtrl->forwardCommand($gui);
	}

	/**
	 * Forward to GUI dispalying cloed actions
	 *
	 * @return null
	 */
	protected function forwardClosedActions()
	{
		$this->gTabs->activateTab(self::CMD_CLOSED_ACTIONS);
		$gui = new Closed\ilClosedActionsTableGUI($this, $this->plugin_object, $this->actions);
		$this->gCtrl->forwardCommand($gui);
	}

	/**
	 * Set the tabs
	 */
	protected function setTabs()
	{
		$open_link = $this->gCtrl->getLinkTarget($this, self::CMD_OPEN_ACTIONS);
		$closed_link = $this->gCtrl->getLinkTarget($this, self::CMD_CLOSED_ACTIONS);

		$this->gTabs->addTab(self::CMD_OPEN_ACTIONS, $this->plugin_object->txt("open_actions"), $open_link);
		$this->gTabs->addTab(self::CMD_CLOSED_ACTIONS, $this->plugin_object->txt("closes_actions"), $closed_link);
	}
}
