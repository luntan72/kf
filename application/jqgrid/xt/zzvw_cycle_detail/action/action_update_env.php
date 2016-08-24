<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_action_update_env extends xt_zzvw_cycle_detail_action_jqgrid{

   public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$element = $this->caclIDs($params);
		if($element == "error")
			return "error";
		foreach($element as $element_id)
		$res = $this->tool->query("select * from cycle_detail where id = {$element_id}");	
		if($row = $res->fetch()){
			$result = $this->tool->query("select * from cycle_detail where cycle_id = {$row['cycle_id']} and prj_id = {$row['prj_id']} 
				and compiler_id = {$row['compiler_id']} and build_target_id = {$row['build_target_id']} 
				and codec_stream_id = {$row['codec_stream_id']} and test_env_id = {$params['test_env_id']}");
			if($info = $result->fetch()){
				if($row['result_type_id']){
					if($info['result_type_id'])
						$this->tool->delete("cycle_detail", "id=".$row['id']);
					else
						$this->tool->delete("cycle_detail", "id=".$info['id']);
				}
				else
					$this->tool->delete("cycle_detail", "id=".$row['id']);
					
			}
			else
				$this->tool->update("cycle_detail", array('test_env_id'=>$params['select_item']), "id = {$element_id}");
		}
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Test Env';
		$view_params['view_file'] = 'select_item.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
		$res = $this->tool->query("select id, name from test_env where isactive = ".ISACTIVE_ACTIVE);
		$view_params['items'][0] = '';
		while($row = $res->fetch()){
			$id = $row['id'];
			$name = $row['name'];
			$view_params['items'][$id] = compact('id', 'name');
		}
		return $view_params;
	}
	
}

?>