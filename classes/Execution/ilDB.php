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
						, "schedule" => array('text', $execution->getScheduled()->get(IL_CAL_DATETIME))
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
	public function update(CaT\Plugins\AutomaticUserAdministration\Execution\Execution $execution)
	{
		$where = array("id" => array('integer', $execution->getId()));

		$run_date = null;
		if ($execution->getRunDate() !== null) {
			$run_date = $execution->getRunDate()->get(IL_CAL_DATETIME);
		}

		$values = array("schedule" => array('text', $execution->getScheduled()->get(IL_CAL_DATETIME))
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
	public function getOpenExecutions()
	{
		$query = "SELECT id, schedule, action, run_date, initiator, inducement\n"
				." FROM ".self::TABLE_NAME."\n"
				." WHERE run_date IS NULL";

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
	public function getClosedExecutions()
	{
		$query = "SELECT id, schedule, action, run_date, initiator, inducement\n"
				." FROM ".self::TABLE_NAME."\n"
				." WHERE run_date IS NOT NULL";

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
		return new Execution(
			(int)$row["id"],
			(int)$row["initiator"],
			$row["inducement"],
			new \ilDateTime($row["schedule"], IL_CAL_DATE),
			unserialize($row["action"]),
			new \ilDateTime($row["run_date"], IL_CAL_DATE)
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
					"schedule" => array(
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
