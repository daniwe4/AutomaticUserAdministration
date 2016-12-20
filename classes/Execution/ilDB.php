<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

class ilDB implements DB
{
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
	}
}
