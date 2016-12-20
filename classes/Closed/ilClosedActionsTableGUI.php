<?php

namespace CaT\Plugins\AutomaticUserAdministration\Closed;

require_once("Services/Table/classes/class.ilTable2GUI.php");
require_once(__DIR__."/class.ilClosedActionsGUI.php");

/**
 * Table gui to show all closed actions
 */
class ilClosedActionsTableGUI extends \ilTable2GUI
{
		/**
	 * @var \ilClosedActionsGUI
	 */
	protected $parent_object;

	/**
	 * @var \ilAutomaticUserAdministrationPlugin
	 */
	protected $plugin_object;

	public function __construct(\ilClosedActionsGUI $parent_object, \ilAutomaticUserAdministrationPlugin $plugin_object)
	{
		parent::__construct($parent_object);

		$this->parent_object = $parent_object;
		$this->plugin_object = $plugin_object;

		$this->configurateTable();
	}

	public function fillRow($row)
	{
		$this->tpl->setVariable("SCHEDULED", $row["scheduled"]);
		$this->tpl->setVariable("ACTION", $row["action"]);
		$this->tpl->setVariable("LOGIN", $row["login"]);
		$this->tpl->setVariable("FIRSTNAME", $row["firstname"]);
		$this->tpl->setVariable("LASTNAME", $row["lastname"]);
		$this->tpl->setVariable("ROLES", $row["roles"]);
		$this->tpl->setVariable("INITIATOR", $row["initiator"]);
	}

	/**
	 * Configurate the table
	 *
	 * @return null
	 */
	protected function configurateTable()
	{
		$this->setEnableTitle(true);
		$this->setTitle($this->txt("closed_actions"));
		$this->setTopCommands(false);
		$this->setEnableHeader(true);
		$this->setExternalSorting(false);
		$this->setExternalSegmentation(false);
		$this->setRowTemplate("tpl.closed_actions_table_row.html", $this->plugin_object->getDirectory());
		$this->setShowRowsSelector(false);

		$this->addColumn($this->txt("scheduled"), "scheduled");
		$this->addColumn($this->txt("action"), "action");
		$this->addColumn($this->txt("login"), "login");
		$this->addColumn($this->txt("firstname"), "firstname");
		$this->addColumn($this->txt("lastname"), "lastname");
		$this->addColumn($this->txt("roles"));
		$this->addColumn($this->txt("initiator"), "initiator");
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
