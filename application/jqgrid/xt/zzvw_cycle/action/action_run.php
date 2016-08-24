<?php
require_once('action_jqgrid.php');
class xt_zzvw_cycle_action_run extends action_jqgrid{
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['view_file'] = 'run.phtml';
		$view_params['view_file_dir'] = '/jqgrid/xt/zzvw_cycle/view';
		$view_params['blank'] = 'false';
		
		$tokens = array();
		$res = $this->tool->query('select token.id, token.name from token left join station on token.name=station.token where station.station_status_id!=3');
		while($row = $res->fetch())
			$tokens[$row['id']] = $row['name'];
		$users = array();
		$res = $this->tool->query("select nickname, email from useradmin.users left join useradmin.groups_users on groups_users.users_id=users.id where groups_id=1 and status_id=1");
		while($row = $res->fetch()){
			$users[$row['email']] = $row['nickname'];
		}
		$view_params['cols'] = array(
			array('id'=>'token_ids', 'name'=>'token_ids', 'label'=>'Tokens', 'cols'=>5, 'editable'=>true, 'required'=>true, 'DATA_TYPE'=>'text', 'type'=>'select', 
				'editoptions'=>array('value'=>$tokens),
				'formatoptions'=>array('value'=>$tokens)),
			array('id'=>'mailto', 'name'=>'mailto', 'label'=>'Mail To', 'editable'=>true, 'type'=>'checkbox', 'DATA_TYPE'=>'text', 
				'length'=>50, 'editrules'=>array('required'=>true), 'editoptions'=>array('value'=>$users)
				),
			array('id'=>'include_gpu', 'name'=>'include_gpu', 'label'=>'Include GPU Cases', 'cols'=>2, 'editable'=>true, 'defval'=>2, 'type'=>'select', 'DATA_TYPE'=>'int', 
				'editoptions'=>array('value'=>array(1=>'Include GPU Cases', 2=>'Do Not Include GPU Cases')))
		);
// print_r($view_params);		
		return $view_params;
	}
	
	protected function handlePost(){
// print_r($this->params);		
		//创建request，之后调用$scheduler_api -m add --number $number
		// $request_id = 14;
		//需要检查cycle的状态，如果cycle状态是init, waiting, running,则直接返回0
// print_r($this->params);
		$cycle_id = 0;
		if($this->params['table'] == 'zzvw_cycle_detail')
			$cycle_id = $this->params['parent'];
		else
			$cycle_id = $this->params['id'];
// print_r($cycle_id);		
		$res = $this->tool->query("SELECT * FROM cycle WHERE id=$cycle_id");
		if($row = $res->fetch()){
			if(in_array($row['request_status_id'], array(REQUEST_STATUS_INIT, REQUEST_STATUS_WAITING, REQUEST_STATUS_RUNNING)))
				return 0;
		}
		$creater_mail = '';
		$res = $this->tool->query("select nickname, email from useradmin.users left join xt.cycle on users.id=cycle.creater_id where cycle.id=$cycle_id");
		if($row = $res->fetch()){
			$creater_mail = $row['email'];
		}
		if(!isset($this->params['mailto']))
			$this->params['mailto'] = array();
		if(is_array($this->params['mailto'])){
			if(!empty($creater_mail))
				$this->params['mailto'][] = $creater_mail;
			$this->params['mailto'] = implode(',', $this->params['mailto']);
		}
		$request = array('owner_id'=>$this->userInfo->id, 'cycle_id'=>$cycle_id, 
			'request_status_id'=>REQUEST_STATUS_WAITING, 'token_ids'=>$this->params['token_ids'], 'start_time'=>date('Y-m-d H:i:s'), 
			'mailto'=>$this->params['mailto']);
		$request_id = $this->tool->insert('request', $request);
		//将所有case存入request_detail表
		$sql = $this->getSql();
// print_r($sql);
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			$request_detail = array('request_id'=>$request_id, 'cycle_detail_id'=>$row['id'], 'testcase_ver_id'=>$row['testcase_ver_id'], 'result_id'=>0);
			$this->tool->insert('request_detail', $request_detail);
		}
		$this->tool->update('cycle', array('request_id'=>$request_id, 'request_status_id'=>REQUEST_STATUS_WAITING), "id=".$cycle_id);
		
		exec("/opt/dapeng/dpc/bin/dpcClient -m add --id $request_id", $output, $retval);
print_r("output = ");
print_r($output);
print_r("return value = $retval");
		return $request_id;
	}
	
	protected function getSql(){
		//将所有case存入request_detail表
		$sql = "SELECT * FROM cycle_detail where cycle_id={$this->params['id']}";
		if(isset($this->params['include_gpu']) && $this->params['include_gpu'] == 1){
			$gpu_module_id = "193,268,363,364,681"; //GPU_XEGL_L, GPU_FB_L, GPU_DFB_L, GPU_WAYLAND_L, GPU_XWAYLAND_L
			$sql = "SELECT * FROM cycle_detail left join testcase on cycle_detail.testcase_id=testcase.id WHERE cycle_id={$this->params['id']} AND testcase.testcase_module_id NOT IN ($gpu_module_id)";
		}
		return $sql;
	}
}

?>