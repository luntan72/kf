<?php
require_once('action_jqgrid.php');
class xt_zzvw_cycle_action_queryruncycle extends action_jqgrid{
	protected function handlePost(){
// print_r($this->params);		
		$running_status = array(REQUEST_STATUS_RUNNING, REQUEST_STATUS_WAITING);
		$str_running = implode(',', $running_status);
		$res = $this->tool->query("select cycle.id, prj.name as prj, cycle.request_status_id".
			" from cycle left join cycle_prj on cycle_prj.cycle_id=cycle.id".
			" left join prj on prj.id = cycle_prj.prj_id".
			" where cycle.request_status_id IN ($str_running)");
		$cycle_id = $res->fetchAll();
		return json_encode($cycle_id);
	}
}

?>