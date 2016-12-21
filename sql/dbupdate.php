<#1>
<?php
require_once("Customizing/global/plugins/Services/Cron/CronHook/AutomaticUserAdministration/classes/Execution/ilDB.php");
$execution_db = new \CaT\Plugins\AutomaticUserAdministration\Execution\ilDB($ilDB);
$execution_db->install();
?>
