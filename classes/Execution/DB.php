<?php

namespace CaT\Plugins\AutomaticUserAdministration\Execution;

interface DB
{
	/**
	 * Create tables, sequences or default entries
	 *
	 * @return null
	 */
	public function install();

	/**
	 * Get all open executions
	 *
	 * @return CaT\Plugins\AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getOpenExecutions();

	/**
	 * Get all closed executions
	 *
	 * @return CaT\Plugins\AutomaticUserAdministration\Execution\Execution[]
	 */
	public function getClosedExecutions();
}
