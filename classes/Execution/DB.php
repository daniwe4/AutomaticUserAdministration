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
	 * Create a new execution object
	 *
	 * @return \CaT\Plugins\AutomaticUserAdministration\Execution\Execution
	 */
	public function create($initiator_id, $inducement, \ilDateTime $scheduled, \CaT\Plugin\AutomaticUserAdministration\Actions\UserAction $action, \ilDateTime $run_date);

	/**
	 * Update existing execution
	 *
	 * @param \CaT\Plugins\AutomaticUserAdministration\Execution\Execution 	$execution
	 *
	 * @return null
	 */
	public function update(CaT\Plugins\AutomaticUserAdministration\Execution\Execution $execution);

	/**
	 * Delete an execution by id
	 *
	 * @param int 	$id
	 *
	 * @return null
	 */
	public function delete($id);

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
