<?php

use \CaT\Plugins\AutomaticUserAdministration\Open;
use \CaT\Plugins\AutomaticUserAdministration\ilActions;

class ilOpenActionsGUI
{
	const CMD_VIEW = "view";
	const CMD_OPEN_ACTIONS = "openActions";
	const CMD_NEW_ACTION = "newAction";
	const CMD_EDIT_ACTION = "editAction";
	const CMD_DELETE_ACTION = "deleteAction";
	const CMD_DELETE_ACTION_CONFIRM = "deleteActionConfirm";
	const CMD_AUTOCOMPLETE = "userfieldAutocomplete";
	const CMD_SAVE_ACTION = "saveAction";
	const CMD_UPDATE_ACTION = "updateAction";

	/**
	 * @var \ilCtrl
	 */
	protected $gCtrl;

	/**
	 * @var \ilTemplate
	 */
	protected $gTpl;

	/**
	 * @var \ilToolbarGUI
	 */
	protected $gToolbar;

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
		global $ilCtrl, $tpl, $ilToolbar;

		$this->gCtrl = $ilCtrl;
		$this->gTpl = $tpl;
		$this->gToolbar = $ilToolbar;

		$this->parent_object = $parent_object;
		$this->plugin_object = $plugin_object;
		$this->actions = $actions;
	}

	public function executeCommand()
	{
		$cmd = $this->gCtrl->getCmd(self::CMD_VIEW);

		switch ($cmd) {
			case self::CMD_OPEN_ACTIONS:
				$cmd = "view";
				// switch to cmd "view" to avoid function duplicates
			case self::CMD_NEW_ACTION:
			case self::CMD_VIEW:
			case self::CMD_AUTOCOMPLETE:
			case self::CMD_SAVE_ACTION:
			case self::CMD_EDIT_ACTION:
			case self::CMD_UPDATE_ACTION:
			case self::CMD_DELETE_ACTION_CONFIRM:
			case self::CMD_DELETE_ACTION:
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
	protected function view()
	{
		$this->setToolbar();
		$table = new Open\ilOpenActionsTableGUI($this, $this->plugin_object);
		$table->setData($this->actions->getOpenActions());
		$this->gTpl->setContent($table->getHtml());
	}

	/**
	 * Show form for adding action
	 *
	 * @param \ilPropertyFormGUI | null 	$form
	 */
	protected function newAction($form = null)
	{
		if ($form === null) {
			$form = $this->initForm();
		}

		$form->setTitle($this->txt("new"));
		$form->addCommandButton(self::CMD_SAVE_ACTION, $this->txt("save"));
		$form->addCommandButton(self::CMD_VIEW, $this->txt("cancel"));

		$this->gTpl->setContent($form->getHtml());
	}

	/**
	 * Show form for editing action
	 *
	 * @param \ilPropertyFormGUI | null 	$form
	 */
	protected function editAction($form = null)
	{
		if ($form === null) {
			$form = $this->initForm();
			// $id = $this->getActionId();
			// $values = $this->actions->getActionValues($id);
			// $form->setValuesByArray($values);
		}

		$form->setTitle($this->txt("new"));
		$form->addCommandButton(self::CMD_UPDATE_ACTION, $this->txt("update"));
		$form->addCommandButton(self::CMD_VIEW, $this->txt("cancel"));

		$this->gTpl->setContent($form->getHtml());
	}

	/**
	 * Save new action
	 *
	 * @return null
	 */
	protected function saveAction()
	{
		$form = $this->initForm();

		if (!$form->checkInput()) {
			$form->setValuesByPost();
			$this->newAction($form);
			return;
		}

		\ilUtil::sendSuccess($this->txt("save_success"), true);
		$this->gCtrl->redirect($this);
	}

	/**
	 * Update action
	 *
	 * @return null
	 */
	protected function updateAction()
	{
		$form = $this->initForm();

		if (!$form->checkInput()) {
			$form->setValuesByPost();
			$this->newAction($form);
			return;
		}

		\ilUtil::sendSuccess($this->txt("update_success"), true);
		$this->gCtrl->redirect($this);
	}

	/**
	 * Show confirmation gui
	 */
	protected function deleteActionConfirm()
	{
		require_once "./Services/Utilities/classes/class.ilConfirmationGUI.php";
		$confirmation = new \ilConfirmationGUI();

		$confirmation->setFormAction($this->gCtrl->getFormAction($this, self::CMD_DELETE_ACTION));
		$confirmation->setHeaderText($this->txt("confirm_delete_action"));
		$confirmation->setCancel($this->txt("cancel"), self::CMD_VIEW);
		$confirmation->setConfirm($this->txt("delete"), self::CMD_DELETE_ACTION);

		$action_id = $this->getActionId();
		// $action = $this->actions->getActionById($action_id);
		// $confirmation->addItem()

		$confirmation->addHiddenItem("id", $action_id);
		$this->gTpl->setContent($confirmation->getHTML());
	}

	/**
	 * Delete action
	 *
	 * @return null
	 */
	protected function deleteAction()
	{
		$action_id = $this->getActionId();
		// $this->actions->deleteActionById($action_id);

		\ilUtil::sendSuccess($this->txt("delete_success"), true);
		$this->gCtrl->redirect($this);
	}

	/**
	 * Init action form
	 *
	 * @return \ilPropertyFormGUI
	 */
	protected function initForm()
	{
		require_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		require_once("Services/Form/classes/class.ilFormSectionHeaderGUI.php");
		require_once("Services/Form/classes/class.ilMultiSelectInputGUI.php");
		require_once("Services/Form/classes/class.ilDateTimeInputGUI.php");
		require_once("Services/Form/classes/class.ilTextInputGUI.php");
		require_once("Services/GEV/Utils/classes/class.gevRoleUtils.php");

		$form = new \ilPropertyFormGUI();
		$form->setFormAction($this->gCtrl->getFormAction($this));

		$sh = new \ilFormSectionHeaderGUI();
		$sh->setTitle($this->txt("settings"));
		$form->addItem($sh);

		$ti = new \ilTextInputGUI($this->txt("action"), ilActions::F_ACTION);
		$ti->setRequired(true);
		$form->addItem($ti);

		$dt = new \ilDateTimeInputGUI($this->txt("scheduled"), ilActions::F_SCHEDULED);
		$dt->setShowTime(true);
		$dt->setMinuteStepSize(15);
		$form->addItem($dt);

		$sh = new \ilFormSectionHeaderGUI();
		$sh->setTitle($this->txt("user"));
		$form->addItem($sh);

		$autocomplete_link = $this->gCtrl->getLinkTarget($this, self::CMD_AUTOCOMPLETE, "", true);
		$ti = new \ilTextInputGUI($this->txt("login"), ilActions::F_LOGIN);
		$ti->setRequired(true);
		$ti->setDataSource($autocomplete_link);
		$form->addItem($ti);

		$sh = new \ilFormSectionHeaderGUI();
		$sh->setTitle($this->txt("roles"));
		$form->addItem($sh);

		$global_roles = \gevRoleUtils::getInstance()->getGlobalRolesWithDesc();
		asort($global_roles);
		$cbxg = new \ilCheckboxGroupInputGUI("", ilActions::F_ROLES);
		foreach ($global_roles as $key => $value) {
			$option = new ilCheckboxOption($value["title"], $key, $value["description"]);
			$cbxg->addOption($option);
		}
		$form->addItem($cbxg);

		return $form;
	}

	/**
	 * Set toolbar elements
	 */
	protected function setToolbar()
	{
		$this->gToolbar->addButton($this->txt("new"), $this->gCtrl->getLinkTarget($this, self::CMD_NEW_ACTION));
	}

	/**
	 * Get the action menu for single entry
	 *
	 * @param int 		$id
	 *
	 * @return \ilAdvancedSelectionListGUI
	 */
	public function getActionMenu($id)
	{
		include_once("Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php");
		$current_selection_list = new \ilAdvancedSelectionListGUI();
		$current_selection_list->setListTitle($this->txt("actions"));
		$current_selection_list->setId($id);
		$current_selection_list->setAdditionalToggleElement("id".$id, "ilContainerListItemOuterHighlight");

		foreach ($this->getActionMenuItems($id) as $key => $value) {
			$current_selection_list->addItem($value["title"], "", $value["link"], $value["image"], "", $value["frame"]);
		}

		return $current_selection_list->getHTML();
	}

	protected function getActionMenuItems($id)
	{
		$this->gCtrl->setParameter($this, "id", $id);
		$link_edit = $this->memberlist_link = $this->gCtrl->getLinkTarget($this, self::CMD_EDIT_ACTION);
		$link_delete = $this->memberlist_link = $this->gCtrl->getLinkTarget($this, self::CMD_DELETE_ACTION_CONFIRM);
		$this->gCtrl->setParameter($this, "id", null);

		$items = array();
		$items[] = array("title" => $this->txt("edit"), "link" => $link_edit, "image" => "", "frame"=>"");
		$items[] = array("title" => $this->txt("delete"), "link" => $link_delete, "image" => "", "frame"=>"");

		return $items;
	}

	protected function txt($code)
	{
		return $this->plugin_object->txt($code);
	}

	public function userfieldAutocomplete()
	{
		include_once './Services/User/classes/class.ilUserAutoComplete.php';
		$auto = new ilUserAutoComplete();
		$auto->setSearchFields(array('login','firstname','lastname','email'));
		$auto->enableFieldSearchableCheck(false);
		if (($_REQUEST['fetchall'])) {
			$auto->setLimit(ilUserAutoComplete::MAX_ENTRIES);
		}
		echo $auto->getList($_REQUEST['term']);
		exit();
	}

	/**
	 * Get the action id for edit or delete
	 *
	 * @return int
	 */
	protected function getActionId()
	{
		if (isset($_GET["id"]) && $_GET["id"] !== null && $_GET["id"] != "") {
			return $_GET["id"];
		}

		if (isset($_POST["id"]) && $_POST["id"] !== null && $_POST["id"] != "") {
			return $_POST["id"];
		}
	}
}
