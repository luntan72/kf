<?php

require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_remove_combination extends action_jqgrid{
	
	protected function setTool($tool_name = 'common'){
		$this->tool_name = $tool_name;
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Remove Combination';
		$view_params['view_file'] = 'newElement.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
// print_r($params);
		$res = $this->tool->query("select group_concat(prj_id) as prj_ids from cycle_prj where cycle_id=".$params['id']);
		if($info = $res->fetch()){
			$result = $this->tool->query("select id, name from prj where id in (".$info['prj_ids'].")");
			$prj[0] = '';
			while($row = $result->fetch()){
				$prj[$row['id']] = $row['name'];
			}
		}
		$res = $this->tool->query("select group_concat(compiler_id) as compiler_ids from compiler_cycle where cycle_id=".$params['id']);
		if($info = $res->fetch()){
			$result = $this->tool->query("select id, name from compiler where id in (".$info['compiler_ids'].")");
			$compiler[0] = '';
			while($row = $result->fetch()){
				$compiler[$row['id']] = $row['name'];
			}
		}
		$res = $this->tool->query("select group_concat(build_target_id) as build_target_ids from build_target_cycle where cycle_id=".$params['id']);
		if($info = $res->fetch()){
			$result = $this->tool->query("select id, name from build_target where id in (".$info['build_target_ids'].")");
			$build_target[0] = '';
			while($row = $result->fetch()){
				$build_target[$row['id']] = $row['name'];
			}
		}
		$cols = array(
			//array('name'=>'id', 'label'=>'Cycle', 'style'=>"display:none", 'view'=>false, 'editable'=>false, 'type'=>'text', 'defval'=>$params['id']),
			array('name'=>'prj_id', 'label'=>'Project', 'editable'=>true, 'type'=>'select', 'editoptions'=>array('value'=>$prj)),
			array('name'=>'compiler_id', 'label'=>'Compiler', 'editable'=>true, 'type'=>'select', 'editoptions'=>array('value'=>$compiler)),
			array('name'=>'build_target_id', 'label'=>'Build Target', 'editable'=>true, 'type'=>'select', 'editoptions'=>array('value'=>$build_target)),
			array('name'=>'seletct_items', 'label'=>'Combinations', 'editable'=>true, 'type'=>'checkbox')
		);
		$view_params['cols'] = $cols;
		return $view_params;
	}
	
	protected function handlePost(){
		$ret = 0;
		$params = $this->params;
		$sql = "cycle_id={$params['id']}";

		if(!empty($params['select_items'])){
			foreach($params['select_items'] as $select_items){
				$data = json_decode($select_items);
				foreach($data as $k=>$v){
					switch($k){
						case 'prj_id':
							$prj_id = $v;
							break;
						case 'compiler_id':
							$compiler_id = $v;
							break;
						case 'build_target_id':
							$build_target_id = $v;
							break;
					}
				}
				if($prj_id)
					$sql .= " AND prj_id=$prj_id";
				if($compiler_id)
					$sql .= " AND compiler_id=$compiler_id";
				if($build_target_id)
					$sql .= " AND build_target_id=$build_target_id";
// print_r($sql);
				$ret += $this->tool->delete("cycle_detail", $sql);
// print_r($sql);
				$sql = "cycle_id={$params['id']}";
			}
			$ret;
		}
		else{
			if(!empty($params['prj_id']))
				$sql .= " AND prj_id={$params['prj_id']}";
			if(!empty($params['compiler_id']))
				$sql .= " AND compiler_id={$params['compiler_id']}";
			if(!empty($params['build_target_id']))
				$sql .= " AND build_target_id={$params['build_target_id']}";
			$ret = $this->tool->delete("cycle_detail", $sql);
		}
		if($ret)
			$this->processCycle($params['id']);
		return $ret;
	}

	private function processCycle($cycle_id){
		$res = $this->tool->query("select group_concat(distinct detail.prj_id) as prj_ids, group_concat(distinct detail.compiler_id) as compiler_ids, 
			group_concat(distinct detail.build_target_id) build_target_ids, group_concat(distinct tc.testcase_type_id) as testcase_type_ids
			from cycle_detail detail left join testcase tc on tc.id = detail.testcase_id where detail.cycle_id = $cycle_id");
		if($row = $res->fetch()){
			if(!empty($row['prj_ids'])){
				$this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'build_target_ids'=>$row['build_target_ids'],
					'testcase_type_ids'=>$row['testcase_type_ids']), 'id='.$cycle_id);
				$data = array("prj_id"=>$row['prj_ids'], 'compiler_id'=>$row['compiler_ids'], 'build_target_id'=>$row['build_target_ids']);
				$this->tool->updateLinkTables(array("cycle_id"=>$cycle_id), $data);
			}
		}
	}
				
				// if(empty($this->params['id'])&& !empty($this->params['zzvw_mcuauto_request_ids'])){
					// $tables = array('build_target_cycle', 'compiler_cycle', 'cycle_prj', 'cycle_tester', 'cycle_testcase_type');
					// foreach($tables as $table){
							// if(preg_match('/^(.*)_cycle$/', $table, $matches)){
								// $new_table = $matches[1];
							// }
							// else if(preg_match('/^cycle_(.*)$/', $table, $matches)){
								// $new_table = $matches[1];
							// }
							// $keyID = $new_table."_ids";
							// $kID = $new_table."_id";
							// $new_res = $this->tool->query("SELECT id, GROUP_CONCAT(DISTINCT {$kID}) AS lists FROM {$table} WHERE cycle_id={$cycle_id}");
							// if($new_info = $new_res->fetch()){
								// $lists = explode(",", $new_info['lists']);
								// if(count($lists) == 1 && !empty($lists[0])) 
									// $this->tool->delete($table, "id=".$new_info['id']);
							// }
					// }
				// }
				// // update cycle info here
				// $this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'build_target_ids'=>$row['build_target_ids'],
					// 'testcase_type_ids'=>$row['testcase_type_ids'], 'test_env_id'=>1), 'id='.$cycle_id
				// );
				// $res = $this->tool->query("select * from cycle where id=".$cycle_id);
				// if($info = $res->fetch()){
					// if(!empty($row['tester_ids'])){
						// if(empty($info['tester_ids']))
							// $tester_ids = $row['tester_ids'];
						// else
							// $tester_ids = $info['tester_ids'].",".$row['tester_ids'];
						// $this->tool->update("cycle", array('tester_ids'=>$tester_ids), 'id='.$cycle_id);
					// }
				// }
				// # update link table
				// $row['prj_ids'] = explode(",", $row['prj_ids']);
				// $row['compiler_ids'] = explode(",", $row['compiler_ids']);
				// $row['build_target_ids'] = explode(",", $row['build_target_ids']);
				// $row['testcase_type_ids'] = explode(",", $row['testcase_type_ids']);
				// $row['tester_ids'] = explode(",", $row['tester_ids']);
				// $tables = array('build_target_cycle', 'compiler_cycle', 'cycle_prj', 'cycle_tester', 'cycle_testcase_type');
				// foreach($tables as $table){
					// if(preg_match('/^(.*)_cycle$/', $table, $matches)){
						// $new_table = $matches[1];
					// }
					// else if(preg_match('/^cycle_(.*)$/', $table, $matches)){
						// $new_table = $matches[1];
					// }
					// $keyID = $new_table."_ids";
					// $kID = $new_table."_id";
					// foreach($row[$keyID] as $v){
						// if(!empty($v)){
							// $insert = array('cycle_id'=>$cycle_id, $kID=>$v);
							// $result = $this->tool->query("SELECT id FROM {$table} WHERE cycle_id={$cycle_id} AND {$kID}={$v} LIMIT 1");
							// if($info = $result->fetch()){
								// continue;
							// }
							// else{
								// $this->tool->insert($table, $insert);
							// }
						// }
					// }
				// }
			// }
		// }
	// }
}
?>