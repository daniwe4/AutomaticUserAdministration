<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

class ilDB implements DB
{
	const TABLE_NAME = "aua_exections";

	/**
	 * @var \ilDB
	 */
	protected $gDB;

	public function __construct(\ilDB $db)
	{
		$this->gDB = $db;
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
	public function create($initiator_id, \ilDateTime $scheduled, \CaT\Plugin\AutomaticUserAdministration\Actions\UserAction $action, \ilDateTime $run_date)
	{
		$next_id = $this->getNextId();

		$execution = new Execution\Execution($next_id, $initiator_id, $scheduled, $action, $run_date);

		$values = array("id" => array('integer', $execution->getId())
						, "schedule" => array('text', $execution->getScheduled())
						, "action" => array('text', serialize($execution->getAction()))
						, "run_date" => array('text', $execution->getRunDate())
						, "initiator" => array('integer', $execution->getInitatorId())
					);

		$this->gDB->insert(self::TABLE_NAME, $values);

		return $execution;
	}

	/**
	 * @inheritdoc
	 */
	public function update(CaT\Plugins\AutomaticUserAdministration\Execution\Execution $execution)
	{
		$where = array("id" => array('integer', $execution->getId()));

		$values = array("schedule" => array('text', $execution->getScheduled())
						, "action" => array('text', serialize($execution->getAction()))
						, "run_date" => array('text', $execution->getRunDate())
						, "initiator" => array('integer', $execution->getInitatorId())
					);

		$this->gDB->update(self::TABLE_NAME, $values, $where);
	}

	/**
	 * @inheritdoc
	 */
	public function delete($id)
	{
		$query = "DELETE FROM ".self::TABLE_NAME."\n"
				." WHERE id = ".$this->gDB->quote($id, "integer");

		$this->gDB->manipulate($query);
	}

	/**
	 * @inheritdoc
	 */
	public function getOpenExecutions()
	{
		return array();
	}

	/**
	 * @inheritdoc
	 */
	public function getClosedExecutions()
	{
		return array();
	}

	/**
	 * Get next id from sequence
	 *
	 * @return int
	 */
	protected function getNextId()
	{
		return $this->gDB->nextId(self::TABLE_NAME);
	}

	/**
	 * Create execution table
	 *
	 * @return null
	 */
	protected function createTable()
	{
		if (!$this->gDB->tableExists(self::TABLE_NAME)) {
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
							'notnull' 	=> true
					),
					"initiator" => array(
							'type' 		=> 'integer',
							'length' 	=> 4,
							'notnull' 	=> true
					)
				);

			$this->gDB->createTable(self::TABLE_NAME, $fields);
			$this->gDB->createSequence(self::TABLE_NAME);
		}
	}
}
