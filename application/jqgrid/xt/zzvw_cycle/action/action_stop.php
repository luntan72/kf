<?php
require_once('action_jqgrid.php');
class xt_zzvw_cycle_action_stop extends action_jqgrid{
	protected function handlePost(){
// print_r($this->params);		
		$res = $this->tool->query("select * from cycle where id={$this->params['id']}");
		$cycle = $res->fetch();
		$request_id = $cycle['request_id'];
		$request_status_id = $cycle['request_status_id'];
print_r("request_status = $request_status_id");		
		if($request_status_id == REQUEST_STATUS_RUNNING || $request_status_id == REQUEST_STATUS_WAITING){
			$this->tool->update('cycle', array('request_status_id'=>REQUEST_STATUS_STOP), "id=".$cycle['id']);
			$this->tool->update('request', array('request_status_id'=>REQUEST_STATUS_STOP), "id=".$request_id);
			exec("/opt/dapeng/dpc/bin/dpcClient -m stop --id $request_id");
		}
	}
}

?>