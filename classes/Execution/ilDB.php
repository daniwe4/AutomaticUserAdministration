<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

require_once('./Services/Calendar/classes/class.ilDateTime.php');

class ilDB implements DB
{
	const TABLE_NAME = "aua_executions";

	/**
	 * @var \ilDB
	 */
	protected $g_db;

	public function __construct(\ilDB $db)
	{
		$this->g_db = $db;
	}

	/**
	 * @inheritdoc
	 */
	public function install()
	{
		$this->createTable();
	}

	/**
	 * @inheritdoc
	 */
	public function create(
		$initiator_id,
		$inducement,
		\ilDateTime $scheduled,
		\CaT\Plugins\AutomaticUserAdministration\Actions\Action $action
	) {

		$next_id = $this->getNextId();

		$execution = new Execution($next_id, $initiator_id, $inducement, $scheduled, $action);

		$run_date = null;
		if ($execution->getRunDate() !== null) {
			$run_date = $execution->getRunDate()->get(IL_CAL_DATETIME);
		}

		$values = array("id" => array('integer', $execution->getId())
						, "scheduled" => array('text', $execution->getScheduled()->get(IL_CAL_DATETIME))
						, "action" => array('text', serialize($execution->getAction()))
						, "run_date" => array('text', $run_date)
						, "initiator" => array('integer', $execution->getInitatorId())
						, "inducement" => array('text', $execution->getInducement())
						, "last_edit" => array('text', date("Y-m-d H:i:s"))
					);

		$this->g_db->insert(self::TABLE_NAME, $values);

		return $execution;
	}

	/**
	 * @inheritdoc
	 */
	public function update(\CaT\Plugins\AutomaticUserAdministration\Execution\Execution $execution)
	{
		$where = array("id" => array('integer', $execution->getId()));

		$run_date = null;
		if ($execution->getRunDate() !== null) {
			$run_date = $execution->getRunDate()->get(IL_CAL_DATETIME);
		}

		$values = array("scheduled" => array('text', $execution->getScheduled()->get(IL_CAL_DATETIME))
						, "action" => array('text', serialize($execution->getAction()))
						, "run_date" => array('text', $run_date)
						, "initiator" => array('integer', $execution->getInitatorId())
						, "inducement" => array('text', $execution->getInducement())
						, "last_edit" => array('text', date("Y-m-d H:i:s"))
					);

		$this->g_db->update(self::TABLE_NAME, $values, $where);
	}

	/**
	 * @inheritdoc
	 */
	public function delete($id)
	{
		$query = "DELETE FROM ".self::TABLE_NAME."\n"
				." WHERE id = ".$this->g_db->quote($id, "integer");

		$this->g_db->manipulate($query);
	}

	/**
	 * @inheritdoc
	 */
	public function getExecution($id)
	{
		$query = "SELECT id, scheduled, action, run_date, initiator, inducement\n"
				." FROM ".self::TABLE_NAME."\n"
				." WHERE id = ".$this->g_db->quote($id, "integer");

		$res = $this->g_db->query($query);
		$row = $this->g_db->fetchAssoc($res);

		return $this->createExecutionFromDB($row);
	}

	/**
	 * @inheritdoc
	 */
	public function getOpenExecutions($order_column, $order_direction)
	{
		$query = "SELECT id, scheduled, action, run_date, initiator, inducement\n"
				." FROM ".self::TABLE_NAME."\n"
				." WHERE run_date IS NULL";

		if ($order_column && $order_column != "") {
			$query .= " ORDER BY ".$order_column." ".$order_direction;
		}

		$res = $this->g_db->query($query);

		$ret = array();
		while ($row = $this->g_db->fetchAssoc($res)) {
			$ret[$row["id"]] = $this->createExecutionFromDB($row);
		}

		return $ret;
	}

	/**
	 * @inheritdoc
	 */
	public function getOpenExecutionsScheduledFor($date)
	{
		$query = "SELECT id, scheduled, action, run_date, initiator, inducement\n"
				." FROM ".self::TABLE_NAME."\n"
				." WHERE run_date IS NULL\n"
				."     AND scheduled <= ".$this->g_db->quote($date, "text");

		$res = $this->g_db->query($query);

		$ret = array();
		while ($row = $this->g_db->fetchAssoc($res)) {
			$ret[$row["id"]] = $this->createExecutionFromDB($row);
		}

		return $ret;
	}

	/**
	 * @inheritdoc
	 */
	public function getClosedExecutions($order_column, $order_direction)
	{
		$query = "SELECT id, scheduled, action, run_date, initiator, inducement\n"
				." FROM ".self::TABLE_NAME."\n"
				." WHERE run_date IS NOT NULL";

		if ($order_column && $order_column != "") {
			$query .= " ORDER BY ".$order_column." ".$order_direction;
		}

		$res = $this->g_db->query($query);

		$ret = array();
		while ($row = $this->g_db->fetchAssoc($res)) {
			$ret[$row["id"]] = $this->createExecutionFromDB($row);
		}

		return $ret;
	}

	/**
	 * Get an executon object from dbr esult row
	 *
	 * @param string[] 		$row
	 *
	 * @return Execution
	 */
	protected function createExecutionFromDB($row)
	{
		$run_date = null;
		if ($row["run_date"] !== null) {
			$run_date = new \ilDateTime($row["run_date"], IL_CAL_DATETIME);
		}

		return new Execution(
			(int)$row["id"],
			(int)$row["initiator"],
			$row["inducement"],
			new \ilDateTime($row["scheduled"], IL_CAL_DATETIME),
			unserialize($row["action"]),
			$run_date
		);
	}

	/**
	 * Get next id from sequence
	 *
	 * @return int
	 */
	protected function getNextId()
	{
		return (int)$this->g_db->nextId(self::TABLE_NAME);
	}

	/**
	 * Create execution table
	 *
	 * @return null
	 */
	protected function createTable()
	{
		if (!$this->g_db->tableExists(self::TABLE_NAME)) {
			$fields = array(
					"id" => array(
							'type' 		=> 'integer',
							'length' 	=> 4,
							'notnull' 	=> true
					),
					"scheduled" => array(
							'type' 		=> 'timestamp',
							'notnull' 	=> true
					),
					"action" => array(
							'type' 		=> 'clob',
							'notnull' 	=> true
					),
					"run_date" => array(
							'type' 		=> 'timestamp',
							'notnull' 	=> false
					),
					"initiator" => array(
							'type' 		=> 'integer',
							'length' 	=> 4,
							'notnull' 	=> true
					),
					"inducement" => array(
							'type' 		=> 'text',
							'length'	=> '255',
							'notnull'	=> true
					),
					"last_edit" => array(
							'type' 		=> 'timestamp',
							'notnull' 	=> true
					),
				);

			$this->g_db->createTable(self::TABLE_NAME, $fields);
			$this->g_db->createSequence(self::TABLE_NAME);
			$this->g_db->addPrimaryKey(self::TABLE_NAME, array("id"));
		}
	}
}
