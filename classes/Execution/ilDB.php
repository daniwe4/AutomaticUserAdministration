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
