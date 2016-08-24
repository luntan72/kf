<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_update_apollo extends action_jqgrid{
	
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
		if (preg_match("/^(.*)(\d{4}-\d{2}-\d{2})-(\d{6}).*$/i", $params['result_addr'], $matches)){
			$time = '';
			if(preg_match("/^(\d{2})(\d{2})(\d{2})$/i", $matches[3], $mtes)){
				$this->params['finish_time'] = $matches[2]." ".$mtes[1].":".$mtes[2].":".$mtes[3];
				$time = $mtes[1].":".$mtes[2].":".$mtes[3];
			}
			if(empty($time))
				$time = date('H:i:s');
			if($time < "12:00:00"){
				$release = date("Y-m-d",strtotime("-1 day"));
			}
			else
				$release = $matches[2];
			$date = $matches[2]."-".$matches[3];
			$this->params['baseurl'] = $date."/TestResult";
			$rel_id = $this->tool->getElementId("rel", array('name'=>$release, 'rel_category_id'=>2, "owner_id"=>'48'),array('name'));
			$this->tool->getElementId("os_rel", array('rel_id'=>$rel_id, "os_id"=>'4'));			
		}
		if(!empty($rel_id)){
			$playlist = '';
			$playlist = basename($params['playlist'], ".xml");
			$pair_values = array(
				"creater_id"=>TESTER_APOLLO, // Apollo
				"created" => date("Y-m-d H:i:s"),
				"start_date" => date("Y-m-d"),
				"end_date" => date("Y-m-d"),
				"cycle_type_id" => CYCLE_TYPE_FUNCTION, // fun
				"name" => $params['prj']."-".$this->params['finish_time']."-".$playlist."-apollorun",
				"tester_ids"=>TESTER_APOLLO,
				"group_id"=>GROUP_CODEC, // Codec
				"testcase_type_ids"=>TESTCASE_TYPE_CODEC, // Codec
				'cycle_status_id'=>CYCLE_STATUS_FROZEN, // frozen
				'rel_id'=>$rel_id,
			);
			$prj_id = $this->tool->getExistedId("prj", array('name'=>strtoupper($params['prj'])),array('name'));          
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
			$this->params['prj_ids'] = $prj_id;
			$this->parse($params['result_addr'], $parse_result);
			$this->process($parse_result);
		}
		return $cycle_id;
	}
	
	protected function parse($fileName, &$parse_result){
		$parser = xml_parser_create();
		$vals = array();
		if($data = file_get_contents($fileName))
		   xml_parse_into_struct($parser,$data,$vals,$index);
		$i = 0;
		xml_parser_free($parser);
		if(!empty($vals)){
			foreach($vals as $key=>$val){
				$val['tag'] = strtolower(trim($val['tag']));
				if(!empty($val['tag'])){
					if($val['tag'] == 'testcase'){
						if(empty($val['attributes']))
							continue;
						$i++;
						$parse_result[$i]['codec_stream'] = trim($val['attributes']['ID']);
						// $parse_result[$i]['stream']['result'] = strtolower(trim($val['attributes']['RESULT']));//'Sucess', 'Failure', 'No Test';
					}
					else if(($val['tag'] == 'failedscene')){
						$parse_result[$i]['comment'] = trim($val['attributes']['MESSAGE']);
					}
					else if($val['tag'] == 'trickmode'){
						if(empty($val['attributes']))
							continue;
						$tag = strtolower($val['attributes']['TAG']);
						// when $tag == simple, total result is used to update update comment
						$parse_result[$i]['trickmodes'][$tag] = strtolower(trim($val['attributes']['RESULT']));
					}
					else if($val['tag'] == 'operation'){
						if($tag != 'simple')
							continue;
						if(empty($val['attributes']))
							continue;
						$action = strtolower(trim($val['attributes']['TAG']));
						$result = strtolower(trim($val['attributes']['RESULT']));
						if('pause' == $action || 'resume' == $action || 'play' == $action){
							$parse_result[$i]['trickmodes'][$action] = $result;
						}
						else if('seek' == $action){
							if(empty($parse_result[$i]['trickmodes'][$action]))
								$parse_result[$i]['trickmodes'][$action] = $result;
							else
								$parse_result[$i]['trickmodes'][$action."tomesc"] = $result;
						}
					}
				}
			}
		}
	}
	
	protected function process($parse_result){
		if(empty($this->params['compiler_ids']))
			$this->params['compiler_ids'] = 1;
		if(empty($this->params['build_target_ids']))
			$this->params['build_target_ids'] = 1;
		if(empty($this->params['test_env_id']))
			$this->params['test_env_id'] = 1;
		$auto = $update_auto = 0;
		$stream = $updateStreams = array();
		$key = $this->tool->getElementId("log_key", array("server"=>"10.192.225.195", "directory"=>"latestBuild/apollo"));
		foreach($parse_result as $k=>$data){
			if(empty($data['codec_stream']))
				continue;
			$res = $this->tool->query("select id, name from codec_stream where code = '".$data['codec_stream']."'");
			if($info = $res->fetch()){
				$codec_stream_id = $info['id'];
				$stream_name = trim($info['name']);
			}
			else
				continue;
			$tm_res = array();
			if(empty($data['trickmodes']))
				continue;
			
			// if($data['stream']['result'] == 'failure'){
				// $trickmodes_res = array_unique($data['trickmodes']);
				// if(count($trickmodes_res) == 1 && in_array('success', $trickmodes_res)){
					// $stream_failure = true;
				// }
			// }
			
			if(isset($data['trickmodes']['play']) && isset($data['trickmodes']['playtoend']))
				unset($data['trickmodes']['play']);
			if(isset($data['trickmodes']['pause']) && isset($data['trickmodes']['resume']) && isset($data['trickmodes']['pauseresume'])){
				unset($data['trickmodes']['pause']);
				unset($data['trickmodes']['resume']);
			}
			
			$simple_failure = false;
			foreach($data['trickmodes'] as $tm0=>$res0){
				switch($tm0){
					case 'play':
					case 'playtoend':
						$tm = 'Android_Playback';
						$tm_res[$tm][] = $res0;
						$tm_res['Android_Exit'][] = $res0;
						break;
					case 'seek':
					case 'seektomsec':
						$tm = 'Android_Seek';
						$tm_res[$tm][] = $res0;
						break;
					case 'pause':
					case 'resume':
						$tm = 'Android_Pause_Resume';
						$tm_res[$tm][] = $res0;
						break;
					case 'speed':
						$tm = 'Android_Trick_Mode';
						$tm_res[$tm][] = $res0;
						break;
					case 'pauseexit':
						$tm = 'Android_Pause_Exit';
						$tm_res[$tm][] = $res0;
						break;
					case 'pauseseek':
						$tm = 'Android_Pause_Seek_Resume';
						$tm_res[$tm][] = $res0;
						break;
					case 'pauseresume':
						$tm = 'Android_Pause_Resume';
						$tm_res[$tm][] = $res0;
						break;
					case 'simple':
						if($res0 == "failure")
							$simple_failure = true;
						break;
				}
			}
			
			if(empty($tm_res))
				continue;
			foreach($tm_res as $tm=>$res){
				$result_type_id = RESULT_TYPE_BLANK;
				$testcase_id = $this->tool->getExistedId("testcase", array('code'=>$tm), array('code'));
				if('error' == $testcase_id)
					continue;
				$sql_res = $this->tool->query("select ptv.* from prj_testcase_ver ptv".
					" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
					" left join testcase on testcase.id = ptv.testcase_id".
					" where ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.") and testcase.isactive = ".ISACTIVE_ACTIVE.
					" and ptv.prj_id={$this->params['prj_ids']} and ptv.testcase_id=$testcase_id");
				if($sql_info = $sql_res->fetch()){
					$testcase_ver_id = $sql_info['testcase_ver_id'];
				}
				$update = array('finish_time'=>$this->params['finish_time'], 'comment'=>"update by apollo", 'testcase_ver_id'=>$testcase_ver_id,
					'tester_id'=>TESTER_APOLLO, 'logs'=>json_encode(array($key=>array($this->params['baseurl']."/[".$data['codec_stream']."]".$stream_name."/simple/test.log"))));				
				$res = array_unique($res);
				if(1 == count($res) && in_array('success', $res)){//pass
					$result_type_id = $this->tool->getResultId('pass');
				}
				else if(in_array('failure', $res)){// failure
					$result_type_id = $this->tool->getResultId('fail');
				}
				else{//not test
					$result_type_id = $this->tool->getResultId('not test');
				}
				if(!empty($data['comment']))
					$update['comment'] = $data['comment']."---update by apollo";  
					
				$update['result_type_id'] = $result_type_id;  				
				$cond ="cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
					" AND test_env_id = {$this->params['test_env_id']}".
					" AND codec_stream_id = {$codec_stream_id}".
					" AND prj_id={$this->params['prj_ids']}".
					" AND compiler_id={$this->params['compiler_ids']}".
					" AND build_target_id={$this->params['build_target_ids']}";
				$result = $this->tool->query("select * from cycle_detail where $cond");
				if($row = $result->fetch()){
					$this->tool->update("cycle_detail", $update, "id=".$row['id']);
					$this->tool->updatelastresult($row['id']);	
				}
				else{
					$auto ++;
					$insert = array('cycle_id'=>$this->params['id'], 'testcase_id'=>$testcase_id, 'codec_stream_id'=>$codec_stream_id, 'testcase_ver_id'=>$update['testcase_ver_id'],
						'prj_id'=>$this->params['prj_ids'], 'compiler_id'=>$this->params['compiler_ids'], 'build_target_id'=>$this->params['build_target_ids'],
						'result_type_id'=>$result_type_id, 'finish_time'=>$this->params['finish_time'], 'comment'=>$update['comment'], 'tester_id'=>TESTER_APOLLO, 'test_env_id'=>$this->params['test_env_id'],
						'logs'=>json_encode(array($key=>array($this->params['baseurl']."/[".$data['codec_stream']."]".$stream_name."/simple/test.log"))));
					$affetcID = $this->tool->insert("cycle_detail", $insert);
					$this->tool->updatelastresult($affetcID);		
				}
			}
		}
		// if(!empty($update_auto))
// print_r($update_auto." streams have been update"."\n<br />" );
	}
}
?>