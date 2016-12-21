<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

require_once("Services/Table/classes/class.ilTable2GUI.php");
require_once(__DIR__."/class.ilOpenExecutionsGUI.php");

/**
 * Table gui to show all open actions
 */
class ilOpenExecutionsTableGUI extends \ilTable2GUI
{
	/**
	 * @var \ilOpenExecutionGUI
	 */
	protected $parent_object;

	/**
	 * @var \ilAutomaticUserAdministrationPlugin
	 */
	protected $plugin_object;

	public function __construct(\ilOpenExecutionsGUI $parent_object, \ilAutomaticUserAdministrationPlugin $plugin_object)
	{
		parent::__construct($parent_object);

		$this->parent_object = $parent_object;
		$this->plugin_object = $plugin_object;

		$this->configurateTable();
	}

	public function fillRow($execution)
	{
		$initiator = $execution->getInitator();
		$users = $execution->getAction()->getUserCollection()->getUsers();
		$roles = $execution->getAction()->getRoles();
		$role_names = $this->getNameForRoles($roles);
		$user = new \ilObjUser($users[0]);

		$this->tpl->setVariable("SCHEDULED", $execution->getScheduled()->get(IL_CAL_FKT_DATE, "d.m.Y H:i:s"));
		$this->tpl->setVariable("INDUCEMENT", $execution->getInducement());
		$this->tpl->setVariable("LOGIN", $user->getLogin());
		$this->tpl->setVariable("FIRSTNAME", $user->getFirstname());
		$this->tpl->setVariable("LASTNAME", $user->getLastname());
		$this->tpl->setVariable("ROLES", implode(", ", $role_names));
		$this->tpl->setVariable("INITIATOR", $initiator->getLogin());
		$this->tpl->setVariable("ACTIONS", $this->parent_object->getActionMenu($execution->getId()));
	}

	/**
	 * Configurate the table
	 *
	 * @return null
	 */
	protected function configurateTable()
	{
		$this->setEnableTitle(true);
		$this->setTitle($this->txt("open_executions"));
		$this->setTopCommands(false);
		$this->setEnableHeader(true);
		$this->setExternalSorting(true);
		$this->setExternalSegmentation(false);
		$this->setRowTemplate("tpl.open_executions_table_row.html", $this->plugin_object->getDirectory());
		$this->setShowRowsSelector(false);

		$this->addColumn($this->txt("scheduled"), "scheduled");
		$this->addColumn($this->txt("inducement"), "inducement");
		$this->addColumn($this->txt("login"));
		$this->addColumn($this->txt("firstname"));
		$this->addColumn($this->txt("lastname"));
		$this->addColumn($this->txt("roles"));
		$this->addColumn($this->txt("initiator"));
		$this->addColumn($this->txt("actions"));
	}

	protected function getNameForRoles(array $roles)
	{
		$ret = array();
		foreach ($roles as $role_id) {
			$ret[] = \ilObject::_lookupTitle($role_id);
		}

		return $ret;
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
