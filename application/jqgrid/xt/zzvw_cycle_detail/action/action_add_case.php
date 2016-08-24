<?php

require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_add_case extends action_jqgrid{

   protected function setTool($tool_name = 'common'){
		$this->tool_name = $tool_name;
	}
   
   public function handlePost(){
		//只添加case，env和codec在case 添加成功之后在detail中添加
		//$params = $this->parseParams();
		$params = $this->params;
		$real_table = $this->get('real_table');
		$sql = "SELECT test_env_id FROM cycle WHERE id=".$params['parent'];
		$res = $this->tool->query($sql);
		if($info = $res->fetch()){
			$test_env_id = $info['test_env_id'];
		}
		else
			return "error";
		if(empty($this->params['prj_id']))
			return;
		if(!is_array($this->params['prj_id']))
			$this->params['prj_id'] = array($this->params['prj_id']);
		$cycle = $this->tool->getlinkFieldIds("cycle", $params['parent']);	
		$cycle['test_env_id'] = $test_env_id;
		
		$sql = "SELECT prj_testcase_ver.prj_id, prj_testcase_ver.testcase_ver_id, prj_testcase_ver.testcase_id FROM prj_testcase_ver". 
			" LEFT JOIN testcase_ver ON testcase_ver.id = prj_testcase_ver.testcase_ver_id".
			" LEFT JOIN testcase ON testcase.id = prj_testcase_ver.testcase_id";
		foreach($this->params['prj_id'] as $prj_id){
			foreach($params['id'] as $testcase_id){
				$tmp_sql = $sql." WHERE prj_testcase_ver.prj_id={$prj_id}".
					" AND prj_testcase_ver.testcase_id={$testcase_id}".
					" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.", ".EDIT_STATUS_GOLDEN.")".
					" AND testcase.isactive = ".ISACTIVE_ACTIVE.
					" LIMIT 1";
				$tmp_res = $this->tool->query($tmp_sql);
				if($row = $tmp_res->fetch()){
					foreach($cycle['compiler_id'] as $compiler_id){
						if(empty($compiler_id))continue;
						foreach($cycle['build_target_id'] as $build_target_id){
							if(empty($build_target_id))continue;
							$detail_sql = "SELECT * FROM cycle_detail WHERE cycle_id = {$params['parent']}".
								" AND testcase_id = {$row['testcase_id']} AND prj_id = {$row['prj_id']}".
								" AND compiler_id = {$compiler_id} AND build_target_id = {$build_target_id}".
								" AND codec_stream_id = 0";//compiler + test_env + build_target
							$detail_res = $this->tool->query($detail_sql);
							if($detail_row = $detail_res->fetch()){
								// $isexist = true;
								//ver不等时，update
								if(empty($detail_row['codec_stream_id'])){
									$update = array();
									if ($detail_row['testcase_ver_id'] != $row['testcase_ver_id'])
										$update['testcase_ver_id'] = $row['testcase_ver_id'];
									//如果result_type_id不为0时，如果replaced，则置0，
									if ($detail_row['result_type_id'] != RESULT_TYPE_BLANK){
										if ($params['replaced']){//replace所有case的result_type_id为0
											$update['result_type_id'] = RESULT_TYPE_BLANK;
											$update['finish_time'] = 0;
										}
									}
									if(!empty($update))
										$this->tool->update($real_table, $update, "id=".$detail_row['id']);
								}
							}
							else{
								$insert = array('cycle_id'=>$params['parent'], 'testcase_ver_id'=>$row['testcase_ver_id'], 'testcase_id'=>$row['testcase_id'], 
									'result_type_id'=>RESULT_TYPE_BLANK, 'codec_stream_id'=>0, 'test_env_id'=>$cycle['test_env_id'], 'compiler_id'=>$compiler_id, 
									'build_target_id'=>$build_target_id, 'prj_id'=>$row['prj_id'], 'finish_time'=>0);
								$this->tool->insert($real_table, $insert);
							}
						}
					}
				}
			}
		}
		// $this->processCycle($params['parent']);
	}
	
	private function processCycle($cycle_id){
		$res = $this->tool->query("select group_concat(distinct detail.prj_id) as prj_ids, group_concat(distinct detail.compiler_id) as compiler_ids, 
			group_concat(distinct detail.build_target_id) build_target_ids, group_concat(distinct tc.testcase_type_id) as testcase_type_ids
			from cycle_detail detail left join testcase tc on tc.id = detail.testcase_id where detail.cycle_id = $cycle_id");
		if($row = $res->fetch()){
			if(!empty($row['prj_ids'])){
				$this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'build_target_ids'=>$row['build_target_ids'],
					'testcase_type_ids'=>$row['testcase_type_ids']), 'id='.$cycle_id
				);
			}
		}
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Add Type';
		$view_params['view_file'] = 'add_case_type.phtml';
		$view_params['view_file_dir'] = '/jqgrid/xt/'.$this->get('table')."/view";
		$view_params['blank'] = 'false';
		$view_params['mcu_add'] = false;
		
		$res = $this->tool->query("select group_id from cycle where id = {$params['parent']}");
		if($row = $res->fetch()){
			if(GROUP_KSDK == $row['group_id'] || GROUP_USB == $row['group_id'])
				$view_params['mcu_add'] = true;
		}
		return $view_params;
	}
}

?>