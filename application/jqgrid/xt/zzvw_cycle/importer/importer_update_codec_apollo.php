<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_update_codec_gvb.php');

class xt_zzvw_cycle_importer_update_codec_apollo extends xt_zzvw_cycle_importer_update_codec_gvb{
	protected function parse($fileName){
		$parser = xml_parser_create();
		if (!($fp = fopen($fileName, "r"))) {
			die("could not open XML input");
		}
		if($data = fread($fp, filesize($fileName)))
		   xml_parse_into_struct($parser,$data,$vals,$index);

		xml_parser_free($parser);
		$i = 0;
		foreach($vals as $key=>$val){
// print_r($val);
// print_r("\n<BR />");
			$val['tag'] = strtolower(trim($val['tag']));
			if(!empty($val['tag'])){
				if($val['tag'] == 'testcase'){
					if(empty($val['attributes']))
						continue;
					$i++;
					$this->parse_result[$i]['codec_stream'] = trim($val['attributes']['ID']);
					// $this->parse_result[$i]['stream']['result'] = strtolower(trim($val['attributes']['RESULT']));//'Sucess', 'Failure', 'No Test';
				}
				else if(($val['tag'] == 'failedscene')){
					$this->parse_result[$i]['comment'] = trim($val['attributes']['MESSAGE']);
				}
				else if($val['tag'] == 'trickmode'){
					if(empty($val['attributes']))
						continue;
					$tag = strtolower($val['attributes']['TAG']);
					$this->parse_result[$i]['trickmodes'][$tag] = strtolower(trim($val['attributes']['RESULT']));
					
				}
				else if($val['tag'] == 'operation'){
					if($tag != 'simple')
						continue;
					if(empty($val['attributes']))
						continue;
					$action = strtolower(trim($val['attributes']['TAG']));
					$result = strtolower(trim($val['attributes']['RESULT']));
					if('pause' == $action || 'resume' == $action || 'play' == $action){
						$this->parse_result[$i]['trickmodes'][$action] = $result;
					}
					else if('seek' == $action){
						if(empty($this->parse_result[$i]['trickmodes'][$action]))
							$this->parse_result[$i]['trickmodes'][$action] = $result;
						else
							$this->parse_result[$i]['trickmodes'][$action."tomsec"] = $result;
					}
				}
			}
		}
	}
	
	protected function process(){
		if(!is_array($this->params['prj_ids']))
				$this->params['prj_ids'] = array($this->params['prj_ids']);
		if(!is_array($this->params['compiler_ids']))
			$this->params['compiler_ids'] = array($this->params['compiler_ids']);
		if(!is_array($this->params['build_target_ids']))
			$this->params['build_target_ids'] = array($this->params['build_target_ids']);
		$auto = $update_auto = 0;
		$stream = $updateStreams = array();
		foreach($this->parse_result as $k=>$data){
			if(empty($data['codec_stream']))
				continue;
			$codec_stream_id = $this->tool->getExistedId("codec_stream", array('code'=>$data['codec_stream']), array('code'));
			if($codec_stream_id == 'error')
				continue;
			$tm_res = array();
			if(empty($data['trickmodes']))
				continue;

			// if($data['stream']['result'] == 'failure'){
				// $trickmodes_res = array_unique($data['trickmodes']);
				// if(count($trickmodes_res) == 1 && in_array('success', $trickmodes_res)){
// //print_r($data['codec_stream']."\n<BR />");
// //print_r('total fail'."\n<BR />");
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
				$finish_time = 0;
				$result_type_id = RESULT_TYPE_BLANK;
				$testcase_id = $this->tool->getExistedId("testcase", array('code'=>$tm), array('code'));
				if('error' == $testcase_id)
					continue;
				// if(in_array('not test', $res))//not test
					// continue;
				$res = array_unique($res);
				if(in_array('success', $res) && 1 == count($res)){
					$result_type_id = $this->tool->getResultId('pass');// pass
					if('error' == $result_type_id)//if('error' == $result_type_id || RESULT_TYPE_BLANK == $result_type_id)
						continue;
					$finish_time = date('Y-m-d H:i:s');
					$update = array('result_type_id'=>$result_type_id, 'finish_time'=>$finish_time, 'comment'=>'update by apollo', 'tester_id'=>$this->params['owner_id']);
					if($simple_failure){
						if(!empty($data['comment']))
							$update['comment'] = $data['comment']."---update by apollo";
					}
				}
				elseif(in_array('failure', $res)){// failure
					if(empty($data['comment']))
						continue;
					$update = array('comment'=>$data['comment']."---update by apollo");
				}
				else//not test
					continue;
				$cond ="cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
						" AND codec_stream_id = {$codec_stream_id}".
						" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
						" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
						" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";

				$result = $this->tool->query("select * from cycle_detail where $cond LIMIT 1");
				if($row = $result->fetch()){
					if(0 == $row['result_type_id']){//如果为0，更新之
						$this->tool->update("cycle_detail", $update, "id=".$row['id']);
						$this->tool->updatelastresult($row['id']);
						if(!isset($stream[$codec_stream_id])){
							$auto ++;
							$stream[$codec_stream_id] = $codec_stream_id;
						}
					}
					else if(RESULT_TYPE_PASS == $row['result_type_id']){//如果是1，有可能更新为0
						if(RESULT_TYPE_BLANK == $result_type_id){
							$this->tool->update("cycle_detail", $update, "id=".$row['id']);
						}
						if(!isset($stream[$codec_stream_id])){
							$auto ++;
							$stream[$codec_stream_id] = $codec_stream_id;
						}
					}
					else{
						if(!isset($stream[$codec_stream_id]) && !isset($updateStreams[$codec_stream_id])){
							$update_auto ++;
							$updateStreams[$codec_stream_id] = $codec_stream_id;
						}
					}
						
				}
			}
		}
		if(!empty($auto))
print_r($auto." streams have updated"."\n<br />");
		if(!empty($update_auto))
print_r($update_auto." streams have been update"."\n<br />" );
		if(empty($auto) && empty($update_auto))
print_r("No update here!"."\n<br />" );		
	}
		
};

?>
