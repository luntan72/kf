<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_jqgrid extends action_jqgrid{
	
	protected function setTool($tool_name = 'common'){
		$this->tool_name = $tool_name;
	}
	
}

?>