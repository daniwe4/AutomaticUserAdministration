<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

require_once("Services/Table/classes/class.ilTable2GUI.php");
require_once(__DIR__."/class.ilClosedExecutionsGUI.php");

/**
 * Table gui to show all closed actions
 */
class ilClosedExecutionsTableGUI extends \ilTable2GUI
{
		/**
	 * @var \ilClosedActionsGUI
	 */
	protected $parent_object;

	/**
	 * @var \ilAutomaticUserAdministrationPlugin
	 */
	protected $plugin_object;

	/**
	 * @var \CaT\Plugins\AutomaticUserAdministration\ilActions
	 */
	protected $actions;

	public function __construct(
		\ilClosedExecutionsGUI $parent_object,
		\ilAutomaticUserAdministrationPlugin $plugin_object,
		\CaT\Plugins\AutomaticUserAdministration\ilActions $actions
	) {
		parent::__construct($parent_object);

		$this->parent_object = $parent_object;
		$this->plugin_object = $plugin_object;
		$this->actions = $actions;

		$this->configurateTable();
	}

	public function fillRow($execution)
	{
		$initiator = $execution->getInitator();
		$users = $execution->getAction()->getUserCollection()->getUsers();
		$roles = $execution->getAction()->getRoles();
		$role_names = $this->actions->getNameForRoles($roles);
		$user = new \ilObjUser($users[0]);

		$this->tpl->setVariable("SCHEDULED", $execution->getRunDate()->get(IL_CAL_FKT_DATE, "d.m.Y H:i:s"));
		$this->tpl->setVariable("INDUCEMENT", $execution->getInducement());
		$this->tpl->setVariable("LOGIN", $user->getLogin());
		$this->tpl->setVariable("FIRSTNAME", $user->getFirstname());
		$this->tpl->setVariable("LASTNAME", $user->getLastname());
		$this->tpl->setVariable("ROLES", implode(", ", $role_names));
		$this->tpl->setVariable("INITIATOR", $initiator->getLogin());
	}

	/**
	 * Configurate the table
	 *
	 * @return null
	 */
	protected function configurateTable()
	{
		$this->setEnableTitle(true);
		$this->setTitle($this->txt("closed_executions"));
		$this->setTopCommands(false);
		$this->setEnableHeader(true);
		$this->setExternalSorting(true);
		$this->setExternalSegmentation(false);
		$this->setRowTemplate("tpl.closed_executions_table_row.html", $this->plugin_object->getDirectory());
		$this->setShowRowsSelector(false);

		$this->addColumn($this->txt("scheduled"), "scheduled");
		$this->addColumn($this->txt("inducement"), "inducement");
		$this->addColumn($this->txt("login"));
		$this->addColumn($this->txt("firstname"));
		$this->addColumn($this->txt("lastname"));
		$this->addColumn($this->txt("roles"));
		$this->addColumn($this->txt("initiator"));
	}

	/**
	 * Get translated lang var
	 *
	 * @return string
	 */
	protected function txt($code)
	{
		return $this->plugin_object->txt($code);
	}
}
