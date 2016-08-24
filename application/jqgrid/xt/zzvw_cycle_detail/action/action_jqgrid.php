<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_jqgrid extends action_jqgrid{
	
	protected function init(&$controller){
		parent::init($controller);
		if(in_array('admin', $this->userInfo->roles) || in_array('assistant_admin', $this->userInfo->roles))
			$this->userInfo->isAdmin = true;
	}
	
	protected function setTool($tool_name = 'common'){
		$this->tool_name = $tool_name;
	}
	
	protected function caclIDs($params){
		//$params['id'] = json_decode($params['id']);//检查$params是否非空
		$params['c_f'] = json_decode($params['c_f']);
		if(is_array($params['c_f']))
			$params['c_f'] = array_unique($params['c_f']);
		if(count($params['c_f']) == 1){
			if($params['c_f'][0] == '1'){
				foreach($params['id'] as $k=>$v){
					if(!empty($v)){
							//是虚行，找到codec_stream_id， 找到对应id
						$res = $this->tool->query("SELECT cycle_id, codec_stream_id, test_env_id, prj_id, compiler_id, build_target_id FROM cycle_detail WHERE id=".$v);
						if($info = $res->fetch()){
							if(!empty($info['codec_stream_id'])){
								$sql = "SELECT id FROM ".$this->get('table')." WHERE cycle_id=".$info['cycle_id'].
							" AND codec_stream_id={$info['codec_stream_id']} AND test_env_id={$info['test_env_id']}".
									" AND prj_id={$info['prj_id']} AND build_target_id={$info['build_target_id']} AND compiler_id={$info['compiler_id']}";
								// foreach($params as $key=>$val){
									// if($key != "id" && $key != "element" && $key != "c_f" && $key != "flag" && $key != "codec_stream_name" && $key != "cycle_id" && $key != "replaced" && $key != "logfile_upload" && $key != "new_comment" && $key != "purpose" && $key != "cellName" && $key != "code" && $key != "new_issue_comment"){
										// if(!empty($val)){
											// $str = " AND ".$key."=".$val;
											// $sql .= $str;
										// }
									// }
								// }
								$detail_res = $this->tool->query($sql);
								while($detail = $detail_res->fetch())
									$elements[] = $detail['id'];
							}
						}
					}
				}
			}
			else
				$elements = $params['id'];
		}
		else
			$elements = 'error';
		
		return $elements;
	}
	
	protected function set_field($data, $fvals, $cycle_id, $feild){
		if(!empty($cycle_id)){//可以去掉
			if($feild == 'test_env_id')
				$cond = 'codec_stream_id';
			else if($feild == 'codec_stream_id'){
				$cond = 'test_env_id';
			}
			$condition = " testcase_id={$data['testcase_id']} AND testcase_ver_id={$data['testcase_ver_id']} AND prj_id={$data['prj_id']}".
				 " AND compiler_id={$data['compiler_id']} AND build_target_id={$data['build_target_id']}";
			if($data[$cond])
				$condition .= " AND ".$cond."={$data[$cond]}";
			$condition .= " AND cycle_id={$cycle_id}";
			$d_res = $this->tool->query("SELECT ".$feild." FROM cycle_detail WHERE".$condition);
			while($row = $d_res->fetch()){
				foreach($fvals as $k=>$val){
					if($val == $row[$feild])
						unset($fvals[$k]);
				}
			}
			$d_res = $this->tool->query("SELECT id, ".$feild." FROM cycle_detail WHERE".$condition);
			//查询此条件下该记录的test_env_id是否为空，如果为空，update
			while($row = $d_res->fetch()){
				if(empty($row[$feild])){
					foreach($fvals as $k=>$val){
						if(!empty($val)){
							$this->tool->update('cycle_detail', array($feild=>$val), "id=".$row['id']);
							unset($fvals[$k]);
						}
					}
				}
			}
			if(!empty($fvals)){
				foreach($fvals as $val){
					//insert剩下env的记录
					if($val != $data[$feild]){
						$data = array('cycle_id'=>$cycle_id, 'testcase_id'=>$data['testcase_id'], 'prj_id'=>$data['prj_id'], 'compiler_id'=>$data['compiler_id'],
							'build_target_id'=>$data['build_target_id'], 'testcase_ver_id'=>$data['testcase_ver_id'], 'result_type_id'=>0, $cond=>$data[$cond], $feild=>$val, 'finish_time'=>0);
						$this->tool->insert('cycle_detail', $data);
					}
				}
			}
		}
	}
	
	public function getStatistics( $streamDetail = False ){
		$blank = 0;
		$additional = False;
		$caseLists = array();
		$row = array('total_cases'=>0, 'pass_cases'=>0, 'fail_cases'=>0, 'finished_cases'=>0, 'nt_cases'=>0, 'na_cases'=>0, 
			'ns_cases'=>0, 'nso_cases'=>0, 'ose_cases'=>0, 'rse_cases'=>0, 'die_cases'=>0, 'ifi_cases'=>0);
		$row['parent'] = $this->params['parent'];	
		
		$res = $this->tool->query("SELECT group_id FROM cycle WHERE id={$row['parent']}");
		$cycleInfo = $res->fetch();
		if(in_array($cycleInfo['group_id'], array(GROUP_KSDK, GROUP_USB)))
			$additional = True;
		
		$res = $this->tool->query("SELECT codec_stream_id, test_env_id, prj_id, result_type_id, COUNT(*) AS cases FROM cycle_detail".
			" WHERE cycle_id = {$row['parent']} GROUP BY codec_stream_id, test_env_id, prj_id, result_type_id ORDER BY codec_stream_id");
		while($info = $res->fetch()){
			$caseLists[$info['test_env_id']][$info['prj_id']][$info['codec_stream_id']][$info['result_type_id']] = $info['cases'];
		}
		
		foreach($caseLists as $envID=>$prjInfo){
			foreach($prjInfo as $prjID=>$streamInfo){
				foreach($streamInfo as $streamID=>$data){
					if(WITHOUT_STREAM == $streamID){
						if($streamDetail)
							continue;
						foreach($data as $resultID=>$count){
							switch($resultID){
								case RESULT_TYPE_PASS:
									$row['pass_cases'] += $count;
									break;
								case RESULT_TYPE_FAIL:
									$row['fail_cases'] += $count;
									break;
								case RESULT_TYPE_BLANK:
									$blank += $count;
									break;
								case RESULT_TYPE_NT:
									$row['nt_cases'] += $count;
									break;
								case RESULT_TYPE_NA:
									$row['na_cases'] += $count;
									break;
								case RESULT_TYPE_NS:
									$row['ns_cases'] += $count;
									break;
								case RESULT_TYPE_OPEN_SERIAL_ERROR:
									$row['ose_cases'] += $count;
									break;
								case RESULT_TYPE_NO_SERIAL_OUTPUT:
									$row['nso_cases'] += $count;
									break;
								case RESULT_TYPE_DOWNLOAD_IMAGE_ERROR:
									$row['die_cases'] += $count;
									break;
								case RESULT_TYPE_INTERACT_FILE_ISSUE:
									$row['ifi_cases'] += $count;
									break;
								case RESULT_TYPE_RW_SERIAL_ERROR:
									$row['rse_cases'] += $count;
									break;
							}
							$row['total_cases'] += $count;
						}
					}
					else{
						if(!$streamDetail)
							continue;
						$result_type = array_keys($data);
						if( 1 == count($result_type)){
							switch($result_type[0]){
								case RESULT_TYPE_PASS:
									$row['pass_cases'] += 1;
									break;
								case RESULT_TYPE_FAIL:
									$row['fail_cases'] += 1;
									break;
								case RESULT_TYPE_BLANK:
									$blank += 1;
									break;
							}
						}
						elseif(in_array(RESULT_TYPE_FAIL, $result_type)){
							$row['fail_cases'] += 1;
						}
						elseif(in_array(RESULT_TYPE_BLANK, $result_type)){
							$blank += 1;
						}
						elseif(in_array(RESULT_TYPE_PASS, $result_type)){
							$row['pass_cases'] += 1;
						}
						$row['total_cases'] += 1;
					}
				}
			}
			$row['finished_cases'] = $row['total_cases'] - $blank;
		}
		$passrate = 0;
		$finishrate = 0;
		$color = 'red';
		if ($row['total_cases'] > 0){
			$passrate = number_format($row['pass_cases']/$row['total_cases'] * 100, 2);
			if ($passrate >= 80)
				$color = 'blue';
			else if ($passrate >= 60)
				$color = 'gray';
			$color = 'red';
			$finishrate = number_format($row['finished_cases']/$row['total_cases'] * 100, 2);
			if ($finishrate >= 80)
				$color = 'blue';
			else if ($finishrate >= 60)
				$color = 'gray';
		}
		$row['unfinished_cases'] = $blank;
		$row['pass_cases'] = sprintf("<span style='color:$color'>%-4d[%5.2f%%]</span>", $row['pass_cases'], $passrate);
		$row['finished_cases'] = sprintf("<span style='color:$color'>%-4d[%5.2f%%]</span>", $row['finished_cases'], $finishrate);
		$blue = "<span style='color:blue'>";
		// $row['nso_cases'] = $row['ose_cases'] = $row['rse_cases'] = $row['die_cases'] = $row['ifi_cases'] = 0;
		$statistics = '<strong>Cycle Status: (Unfinished:'.$row['unfinished_cases'].' Finished:'.$row['finished_cases']." Pass:".$row['pass_cases'];
		if(!$streamDetail){
			$statistics .= " NT:$blue".$row['nt_cases']."</span> NA:$blue".$row['na_cases']."</span> NS:$blue".$row['ns_cases'];
		}
		if($additional){
			$statistics .= "</span> No Serial Output:$blue".$row['nso_cases']."</span> Open Serial Err:$blue".$row['ose_cases'].
				"</span> Download Image Err:$blue".$row['die_cases']."</span> Interact File Issue:$blue".$row['ifi_cases'].
				"</span> RW Serial Err:$blue".$row['rse_cases'];
		}
			$statistics .= '<span><span style="color:red"> Fail:'.$row['fail_cases'].'</span>)</strong>';
		return $statistics;
	}
}

?>