<?php
require_once('action_jqgrid.php');
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/action/action_run.php');
class xt_zzvw_cycle_detail_action_run extends xt_zzvw_cycle_action_run{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
		
		// $view_params = $params;
		// $view_params['view_file'] = 'run.phtml';
		// $view_params['view_file_dir'] = '/jqgrid/xt/zzvw_cycle/view';
		// $view_params['blank'] = 'false';
		// $tokens = array();
		// $res = $this->tool->query('select id, name from token');
		// while($row = $res->fetch())
			// $tokens[$row['id']] = $row['name'];
		// $view_params['cols'] = array(
			// array('id'=>'token_ids', 'name'=>'token_ids', 'label'=>'Tokens', 'cols'=>5, 'editable'=>true, 'required'=>true, 'DATA_TYPE'=>'text', 'type'=>'checkbox', 
				// 'editoptions'=>array('value'=>$tokens),
				// 'formatoptions'=>array('value'=>$tokens))
		// );
// print_r($view_params);		
		return $view_params;
	}
	
	protected function getSql(){
		//将所有case存入request_detail表
		$strIds = implode(",", $this->params['id']);
		$sql = "SELECT * FROM cycle_detail where id in ($strIds)";
		if(isset($this->params['include_gpu']) && $this->params['include_gpu'] == 1){
			$gpu_module_id = "193,268,363,364,681"; //GPU_XEGL_L, GPU_FB_L, GPU_DFB_L, GPU_WAYLAND_L, GPU_XWAYLAND_L
			$sql = "SELECT * FROM cycle_detail left join testcase on cycle_detail.testcase_id=testcase.id WHERE id in ($strIds) AND testcase.testcase_module_id NOT IN ($gpu_module_id)";
		}
		return $sql;
	}
}

?>