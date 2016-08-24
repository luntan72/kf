<?php
require_once('action_jqgrid.php');
class xt_zzvw_cycle_action_get_request_status extends action_jqgrid{
	protected function handlePost(){
		$res = $this->tool->query("SELECT request_status_id FROM cycle WHERE id={$this->params['id']}");
		if($row = $res->fetch()){
			return $row['request_status_id'];
		}
		return 0;
	}
}

?>