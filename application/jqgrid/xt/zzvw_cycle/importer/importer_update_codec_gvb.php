<?php
require_once('importer_base.php');

class xt_zzvw_cycle_importer_update_codec_gvb extends importer_base{
	protected $total = 0;
	
	protected function parse($fileName){
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
					$this->parse_result[$code]['code'] = $code;
				}
				if(!empty($code) && isset($this->parse_result[$code])){
					if(preg_match("/ActionGroup=(.*)$/i", $row_data, $mc)){
						$info = explode(",", $mc[1]);
						$this->parse_result[$code][trim($info[0])] = trim($info[1]);
					}
					elseif(preg_match("/^\[Total_Result\](.*)$/i", $row_data, $mc)){
						$this->parse_result[$code]['total_result'] = trim($mc[1]);
					}
					// else if(preg_match("/Total_Result](.*)$/", $row_data, $m))
						// $this->parse_result[$code]['exit'] = trim($m[1]);
				}
			}
		}
	}
	
	protected function process(){
		if(!empty($this->parse_result)){
// print_r($this->params);
			if(!is_array($this->params['prj_ids']))
				$this->params['prj_ids'] = array($this->params['prj_ids']);
			if(!is_array($this->params['compiler_ids']))
				$this->params['compiler_ids'] = array($this->params['compiler_ids']);
			if(!is_array($this->params['build_target_ids']))
				$this->params['build_target_ids'] = array($this->params['build_target_ids']);
				
			$trickmode = array('play', 'pause', 'accurate_seek', 'fast_seek', 'rotate');
			$case = $updateCase = array();
			$stream = $updateStreams = array();
			$stm = array();
			$i = 0;
			foreach($this->parse_result as $streamInfo){
				if(isset($streamInfo['code'])){
					$codec_stream_id = $this->tool->getExistedId('codec_stream', array('code'=>trim($streamInfo['code'])), array('code'));//不更新
// print_r($codec_stream_id);
					if('error' == $codec_stream_id)
						continue;
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
						if(strtolower($result) == 'pass'){
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
							if($testcase_id == 'error'){
								continue;
							}
							$result_type_id = $this->tool->getResultId($result);
							if('error' == $result_type_id || RESULT_TYPE_BLANK == $result_type_id){
								continue;
							}
		                    $cond = "cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
								" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = {$codec_stream_id}".
								" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
								" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
								" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";
							$update = array('result_type_id'=>$result_type_id, 'comment'=>'apollo gvb', 'finish_time'=>date('Y-m-d H:i:s'), 'tester_id'=>$this->params['owner_id']);
							
							$res = $this->tool->query("select * from cycle_detail where $cond LIMIT 1");
							if($row = $res->fetch()){
								$is_stream = true;
								if(!isset($stm[$codec_stream_id]))
									$stm[$codec_stream_id]=$codec_stream_id;
								if(RESULT_TYPE_BLANK == $row['result_type_id']){
									$this->tool->update("cycle_detail", $update, "id=".$row['id']);
									$this->tool->updatelastresult($row['id']);
									if(!isset($stream[$codec_stream_id])){
										$stream[$codec_stream_id] = $codec_stream_id;
									}
								}
								else{
									if(!isset($stream[$codec_stream_id]) && !isset($updateStreams[$codec_stream_id])){
										$updateStreams[$codec_stream_id] = $codec_stream_id;
									}
								}
									
							}
						}
					}
// print_r('is_stream'.$is_stream."\n<br />");
					if(!$is_stream){//不是流，是case吗？
						$cstc_id = $this->tool->getExistedId('testcase', array('code'=>trim($streamInfo['code'])), array('code'));//不更新
							// case + prj = ver_id
						if($cstc_id == 'error')
							continue;
						if($total_result_id == 1){
							$cond = "cycle_id = {$this->params['id']} AND testcase_id = {$cstc_id}".
									" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = 0".
									" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
									" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
									" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";
							$update = array('result_type_id'=>$total_result_id, 'comment'=>'apollo gvb', 'finish_time'=>date('Y-m-d H:i:s'), 'tester_id'=>$this->params['owner_id']);

							$res = $this->tool->query("select * from cycle_detail where $cond");
							if($row0 = $res->fetch()){
								$is_stream = true;
								if(RESULT_TYPE_BLANK == $row0['result_type_id']){
									$this->tool->update("cycle_detail", $update, "id=".$row0['id']);
									$this->tool->updatelastresult($row0['id']);
									if(!isset($case[$cstc_id])){
										$case[$cstc_id] = $cstc_id;
									}
								}
								else{
									if(!isset($case[$cstc_id]) && !isset($updateCase[$cstc_id])){
										$updateCase[$cstc_id] = $cstc_id;
									}
								}	
							}
						}
					}
				}
			}
		if(!empty($stream))
print_r(count($stream)." streams have updated"."\n<br />");
		if(!empty($updateStreams))
print_r(count($updateStreams)." streams have been update"."\n<br />" );
		if(!empty($case))
print_r(count($case)." cases have updated"."\n<br />");
		if(!empty($updateCase))
print_r(count($updateCase)." cases have been update"."\n<br />" );
		if(empty($stream) && empty($updateStreams) && empty($case) && empty($updateCase))
print_r("No update here!"."\n<br />" );		
		}
	}
};

?>
