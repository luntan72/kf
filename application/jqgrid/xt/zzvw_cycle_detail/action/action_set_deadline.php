<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_action_set_deadline extends xt_zzvw_cycle_detail_action_jqgrid{

   public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
// print_r($params);
		$element = $this->caclIDs($params);
		if($element == "error")
			return "error";
		if(strtolower($this->params['deadline']) == 'blank')
			$this->params['deadline'] = 0;
		$res = $this->tool->query("update cycle_detail set deadline='{$this->params['deadline']}' where id in (".implode(",", $element).")");
		if($rowCount = $res->rowCount()){
			return "success";
		}
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Deadline';
		$view_params['view_file'] = 'deadline.phtml';
		$view_params['view_file_dir'] = '/jqgrid/xt/zzvw_cycle_detail/view';
		$view_params['blank'] = 'false';
		return $view_params;
	}
}

?>