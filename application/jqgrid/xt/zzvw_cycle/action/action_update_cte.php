<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_update_cte extends action_jqgrid{	

	protected function handlePost(){
// print_r($this->params);
		$params = $this->params;
		return $this->createCycle($params);
	}
	
	protected function setTool($tool_name = 'common'){
		$this->tool_name = $tool_name;
	}
	
	private function createCycle($params){
		$cycle_id = 0;
		$parse_result = '';
		$this->params['finish_time'] = 0;
		$this->params['fileName'] = $_FILES['uploaded_file']['name'];
		$params['result_addr'] = $_FILES['uploaded_file']['tmp_name'];
		$this->params['finish_time'] = $params['finish_time'];			
		if (preg_match("/^.*(SMP PREEMPT).*\s(.*?)\s(\d+)\s.*(\d{4})$/i", $this->params['rel_version'], $matches)){
			$time = strtotime($matches[3]." ".$matches[2]." ".$matches[4]);
			$release = date("Y-m-d", $time);
// print($release);
			$rel_id = $this->tool->getElementId("rel", array('name'=>$release, 'rel_category_id'=>2, "owner_id"=>'48'),array('name'));
// print_r($rel_id);
			$this->tool->getElementId("os_rel", array('rel_id'=>$rel_id, "os_id"=>'1'));
		}
		if(!empty($rel_id)){
			$pair_values = array(
				"creater_id"=>TESTER_CTE, // Apollo
				"created" => date("Y-m-d H:i:s"),
				"start_date" => date("Y-m-d"),
				"end_date" => date("Y-m-d"),
				"cycle_type_id" => CYCLE_TYPE_FUNCTION, // fun
				"name" => $this->params['prj']."-".$this->params['finish_time']."-gvbrun-".$this->params['rel_name'],
				"tester_ids"=>TESTER_CTE,
				"group_id"=>GROUP_CODEC, // Codec
				"testcase_type_ids"=>TESTCASE_TYPE_CODEC, // Codec
				'cycle_status_id'=>CYCLE_STATUS_FROZEN, // frozen
				'rel_id'=>$rel_id,
			);
// print_r($pair_values);
			$prj_id = $this->tool->getExistedId("prj", array('name'=>$this->params['prj']),array('name'));          
			if ('error' == $prj_id)
				return;
			$pair_values['prj_ids'] = $prj_id;
			$cycle_id = $this->tool->getElementId("cycle", $pair_values, array('name'));
			$data['prj_ids'] = explode(",", $pair_values['prj_ids']);
			$data['compiler_ids'] = array(1);
			$data['build_target_ids'] = array(1);
			$data['testcase_type_ids'] = explode(",", $pair_values['testcase_type_ids']);
			$data['tester_ids'] = explode(",", $pair_values['tester_ids']);
			$tables = array('build_target_cycle', 'compiler_cycle', 'cycle_prj', 'cycle_tester', 'cycle_testcase_type');
			
			foreach($tables as $table){
				if(preg_match('/^(.*)_cycle$/', $table, $matches)){
					$new_table = $matches[1];
				}
				else if(preg_match('/^cycle_(.*)$/', $table, $matches)){
					$new_table = $matches[1];
				}
				$keyID = $new_table."_ids";
				$kID = $new_table."_id";
				foreach($data[$keyID] as $v){
					if(!empty($v)){
						$insert = array('cycle_id'=>$cycle_id, $kID=>$v);
						$result = $this->tool->query("SELECT id FROM {$table} WHERE cycle_id={$cycle_id} AND {$kID}={$v} LIMIT 1");
						if($info = $result->fetch()){
							continue;
						}
						else{
							$this->tool->insert($table, $insert);
						}
					}
				}
			}
			$this->params['id'] = $cycle_id;
// print_r($this->params);
			$this->params['prj_ids'] = $prj_id;
			$this->parse($params['result_addr'], $parse_result);
			$this->process($parse_result);
		}
		// return 'cycle_id:'.$cycle_id;
		$total = $pass = $fail = $nt = 0;
		$res = $this->tool->query("SELECT count(*) as n FROM cycle_detail WHERE cycle_id= {$cycle_id} and result_type_id = ".RESULT_TYPE_PASS);
		if($info = $res->fetch())
			$pass = $info['n'];
		$res = $this->tool->query("SELECT count(*) as n FROM cycle_detail WHERE cycle_id= {$cycle_id} and result_type_id = ".RESULT_TYPE_FAIL);
		if($info = $res->fetch())
			$fail = $info['n'];
		$total = $pass + $fail;
		$nt = 0;
		return $cycle_id.",".$total.",".$pass.",".$fail.",".$nt;
	}
	
	protected function parse($fileName, &$parse_result){
		$str = '';
		$handle = fopen($fileName, 'r');
		if ($handle){
			while(!feof($handle))
			  $data[] = fgets($handle);
			fclose($handle);
		}
		$i = 0;
		if(!empty($data)){
			for($row=0; $row<count($data); $row++){
				$row_data = trim($data[$row]);
				if(preg_match("/^(\S+)\s+(\S+)\s+(\S+).*(\(CTE\).*)$/i", $row_data, $matches)){
					$parse_result[$i]['code'] = trim($matches[1]);
					$parse_result[$i]['result'] = trim($matches[3]);
					$parse_result[$i]['comment'] = trim($matches[4]);
					$i++;
				}
			}
		}
	}
	
	protected function process($parse_result){
		if(!empty($parse_result)){
			if(!is_array($this->params['prj_ids'])){
				$this->params['prj_ids'] = array($this->params['prj_ids']);
			}
			if(empty($this->params['compiler_ids']))
				$this->params['compiler_ids'] = array('1');
			if(empty($this->params['build_target_ids']))
				$this->params['build_target_ids'] = array('1');
			if(empty($this->params['test_env_id']))
				$this->params['test_env_id'] = 1;
			$auto = $update_auto = 0;	
			foreach($parse_result as $caseInfo){
				$testcase_id = $this->tool->getExistedId('testcase', array('code'=>trim($caseInfo['code'])), array('code'));//不更新
				if($testcase_id == 'error')
					continue;
				$res = $this->tool->query("select testcase_ver_id from prj_testcase_ver where prj_id = ".implode(",", $this->params['prj_ids']).
					" AND testcase_id = ".$testcase_id);
				if($info = $res->fetch()){
					$testcase_ver_id = $info['testcase_ver_id'];
				}
				$result_type_id = $this->tool->getResultId(strtolower($caseInfo['result']));
				if('error' == $result_type_id || RESULT_TYPE_BLANK == $result_type_id)
					continue;
				$cond = "cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
						" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = 0".
						" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
						" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
						" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";
				$data = array('cycle_id'=>$this->params['id'], 'testcase_id'=>$testcase_id, 'test_env_id'=>$this->params['test_env_id'],
					'prj_id'=>implode(",", $this->params['prj_ids']), 'compiler_id'=>implode(",", $this->params['compiler_ids']), 
					'build_target_id'=>implode(",", $this->params['build_target_ids']), 'result_type_id'=>$result_type_id, 'comment'=>$caseInfo['comment'], 'finish_time'=>$this->params['finish_time'],
					'tester_id'=>TESTER_CTE);
						
				$update = array('result_type_id'=>$result_type_id, 'comment'=>$caseInfo['comment'], 'finish_time'=>$this->params['finish_time'],
					'tester_id'=>TESTER_CTE);
				$res = $this->tool->query("select id, result_type_id, comment from cycle_detail where {$cond}");
				if($row = $res->fetch()){
					$this->tool->update('cycle_detail', $update, "id=".$row['id']);
					$this->tool->updatelastresult($row['id']);
				}
				else{
					$affectID = $this->tool->insert("cycle_detail", $data);
					$this->tool->updatelastresult($affectID );
				}
			}
		}
	}
}
?>