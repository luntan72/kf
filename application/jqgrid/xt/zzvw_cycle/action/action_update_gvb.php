<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_update_gvb extends action_jqgrid{
	
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
// print_r($params);
		if (preg_match("/^(.*)(\d{4}-\d{2}-\d{2})-(\d{2})(\d{2})(\d{2}).*$/i", $params['result_addr'], $matches)){
			// $date = $matches[2];
			$this->params['baseurl'] = $matches[1];
			$this->params['finish_time'] = $matches[2]." ".$matches[3].":".$matches[4].":".$matches[5];			
		}
		if (preg_match("/^.*(SMP PREEMPT).*\s(.*?)\s(\d+)\s.*(\d{4})$/i", $params['rel_version'], $matches)){
			$time = strtotime($matches[3]." ".$matches[2]." ".$matches[4]);
			$release = date("Y-m-d", $time);
// print($release);
			$rel_id = $this->tool->getElementId("rel", array('name'=>$release, 'rel_category_id'=>2, "owner_id"=>'48'),array('name'));
// print_r($rel_id);
			$this->tool->getElementId("os_rel", array('rel_id'=>$rel_id, "os_id"=>'1'));
		}
		if(!empty($rel_id)){
			$pair_values = array(
				"creater_id"=>TESTER_GVB, // Apollo
				"created" => date("Y-m-d H:i:s"),
				"start_date" => date("Y-m-d"),
				"end_date" => date("Y-m-d"),
				"cycle_type_id" => CYCLE_TYPE_FUNCTION, // fun
				"name" => $params['prj']."-".$this->params['finish_time']."-gvbrun-".$params['rel_name'],
				"tester_ids"=>TESTER_GVB,
				"group_id"=>GROUP_CODEC, // Codec
				"testcase_type_ids"=>TESTCASE_TYPE_CODEC, // Codec
				'cycle_status_id'=>CYCLE_STATUS_FROZEN, // frozen
				'rel_id'=>$rel_id,
			);
// print_r($pair_values);
			$prj_id = $this->tool->getExistedId("prj", array('name'=>$params['prj']),array('name'));          
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
		$res = $this->tool->query("select distinct codec_stream_id as codec_stream_id from cycle_detail where cycle_id = $cycle_id");
		while($row = $res->fetch()){
			$res0 = $this->tool->query("SELECT group_concat(distinct result_type_id) as result_type_id FROM cycle_detail".
				" WHERE cycle_id= {$cycle_id} AND codec_stream_id in ( ".$row['codec_stream_id']." )");
			if($info = $res0->fetch()){
				$total += 1;
				$results = explode(",", $info['result_type_id']);
				if(count($results) == 1 && $results[0] != '2' && $results[0] != '0'){
					switch($results[0]){
						case '1'://pass
							$pass += 1;
							break;
						case '3'://nt
							$nt += 1;
					}
				}	
				else {
					if(in_array('2', $results))
						$fail += 1;
					if(!in_array('2', $results) && !in_array('0', $results) && in_array('1', $results)){
						$pass += 1;
					}
				}	
			}
		}
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
				if(preg_match("/^TestStreamID:(.*)$/i", $row_data, $matches)){
					$code = trim($matches[1]);
					$parse_result[$code]['code'] = $code;
				}
				if(!empty($code) && isset($parse_result[$code])){
					if(preg_match("/ActionGroup=(.*)$/i", $row_data, $mc)){
						$info = explode(",", $mc[1]);
						$parse_result[$code][trim($info[0])] = trim($info[1]);
					}
					elseif(preg_match("/^\[Total_Result\](.*)$/i", $row_data, $mc)){
						$parse_result[$code]['total_result'] = trim($mc[1]);
					}
					// else if(preg_match("/Total_Result](.*)$/", $row_data, $m))
						// $this->parse_result[$code]['exit'] = trim($m[1]);
				}
			}
		}
	}
	
	protected function process($parse_result){
		if(empty($parse_result))
			return;
		if(empty($this->params['compiler_ids']))
			$this->params['compiler_ids'] = 1;
		if(empty($this->params['build_target_ids']))
			$this->params['build_target_ids'] = 1;
		if(empty($this->params['test_env_id']))
			$this->params['test_env_id'] = 1;
			
		$trickmode = array('play', 'pause', 'accurate_seek', 'fast_seek', 'rotate');
		$case = $updateCase = array();
		$stream = $updateStreams = array();
		foreach($parse_result as $streamInfo){
			if(isset($streamInfo['code'])){
				$trickmodes = '';
				$codec_stream_id = $this->tool->getExistedId('codec_stream', array('code'=>trim($streamInfo['code'])), array('code'));//不更新
				if('error' == $codec_stream_id)
					continue;
				$res = $this->tool->query("select testcase_ids from codec_stream where id = ".$codec_stream_id);
				if($row = $res->fetch())
					$trickmodes = $row['testcase_ids'];
					
				if(!empty($trickmodes)){
					if(preg_match("/^,(.*)$/", $trickmodes, $matches))
						$trickmodes = $matches[1];
					$trickmodes = explode(",", $trickmodes);
					//unset($streamInfo['code']);
					if(isset($streamInfo['name']))
						unset($streamInfo['name']);
					if(isset($streamInfo['location']))
						unset($streamInfo['location']);
					if(!empty($streamInfo['accurate_seek']) && !empty($streamInfo['fast_seek'])){
						if(strtolower($streamInfo['accurate_seek']) == 'pass' && strtolower($streamInfo['fast_seek']) == 'pass')
							$streamInfo['seek'] = $streamInfo['pause_seek'] =  'pass';//update
						else
							$streamInfo['seek'] = $streamInfo['pause_seek'] = 'fail';//不更新
						unset($streamInfo['accurate_seek']);
						unset($streamInfo['fast_seek']);
					}
					$case_type = 'Linux_';
					if(!empty($streamInfo['rotate']) && $streamInfo['rotate'] == 'na'){
						unset($streamInfo['rotate']);
					}
					if(!empty($streamInfo['resize']) && $streamInfo['resize'] == 'na'){
						unset($streamInfo['resize']);
					}
					if(!empty($streamInfo['play']))
						$streamInfo['exit'] = $streamInfo['play'];
					if(!empty($streamInfo['fffb']))
						$streamInfo['trick_Mode'] = $streamInfo['fffb'];
					$total_result_id = 0;
					if(isset($streamInfo['total_result']) && strtolower($streamInfo['total_result']) == 'pass')
						$total_result_id = $this->tool->getResultId($streamInfo['total_result']);
					$is_stream = false;					
					foreach($streamInfo as $case=>$result){
						if($case == 'code' || $case == 'total_result')
							continue;
						switch($case){
							case 'play':
								$case = $case_type.ucfirst($case)."back";
								break;
							case 'pause':
								$case = $case_type.ucfirst($case)."_Resume";
								break;
							case 'rotate':
								$case = $case_type.ucfirst($case);
								break;
							case 'seek':
								$case = $case_type.ucfirst($case);
								break;
							case 'pause_seek':
								$case = $case_type.ucfirst($case);
								break;
							case 'exit':
								$case = $case_type.ucfirst($case);
								break;
							case 'trick_Mode':
								$case = $case_type.ucfirst($case);
								break;
							case 'resize':
								$case = $case_type.ucfirst($case);
								break;
							default:
								break;
						}
	// print_r($case."\n<BR />");
						$testcase_id = $this->tool->getExistedId('testcase', array('code'=>trim($case)), array('code'));//不更新
						// case + prj = ver_id
						if($testcase_id == 'error')
							continue;
						if(!in_array($testcase_id, $trickmodes))
							continue;
						$sql_res = $this->tool->query("select ptv.* from prj_testcase_ver ptv".
							" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
							" left join testcase on testcase.id = ptv.testcase_id".
							" where ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.") and testcase.isactive = ".ISACTIVE_ACTIVE.
							" and ptv.prj_id={$this->params['prj_ids']} and ptv.testcase_id=$testcase_id");
						if($sql_info = $sql_res->fetch()){
							$testcase_ver_id = $sql_info['testcase_ver_id'];
						}
						
						$result_type_id = $this->tool->getResultId($result);
						if('error' == $result_type_id || RESULT_TYPE_BLANK == $result_type_id)
							continue;
						
						$cond = "cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
							" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = {$codec_stream_id}".
							" AND prj_id = {$this->params['prj_ids']}".
							" AND compiler_id = {$this->params['compiler_ids']}".
							" AND build_target_id = {$this->params['build_target_ids']}";
						$update = array('result_type_id'=>$result_type_id, 'comment'=>'apollo gvb', 'finish_time'=>$this->params['finish_time'], 'tester_id'=>TESTER_GVB, 'testcase_ver_id'=>$testcase_ver_id);
						
						$res = $this->tool->query("select * from cycle_detail where $cond LIMIT 1");
						if($row = $res->fetch()){
							$this->tool->update("cycle_detail", $update, "id=".$row['id']);
							$this->tool->updatelastresult($row['id']);
						}
						else{
							$insert = array('cycle_id'=>$this->params['id'], 'testcase_id'=>$testcase_id, 'codec_stream_id'=>$codec_stream_id, 'testcase_ver_id'=>$update['testcase_ver_id'],
								'prj_id'=>$this->params['prj_ids'], 'compiler_id'=>$this->params['compiler_ids'], 'build_target_id'=>$this->params['build_target_ids'],
								'test_env_id'=>$this->params['test_env_id'], 'result_type_id'=>$result_type_id, 'finish_time'=>$this->params['finish_time'], 'comment'=>$update['comment'], 'tester_id'=>117);
							$affectID = $this->tool->insert("cycle_detail", $insert);
							$this->tool->updatelastresult($affectID);	
						}
					}
				}
			}
		}
	}
}
?>