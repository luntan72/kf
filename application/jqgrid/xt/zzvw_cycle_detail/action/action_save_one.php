<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_action_save_one extends xt_zzvw_cycle_detail_action_jqgrid{

	public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$element = $this->caclIDs($params);
		if($element == "error")
			return "error";
			
		$data = array();
		if(!empty($params['test_env_id']))
			$data['test_env_id'] = $params['test_env_id'];
		if(!empty($params['result_type_id'])){
			if('-1' == $params['result_type_id'])
				$params['result_type_id'] = RESULT_TYPE_BLANK;
			$data['result_type_id'] = $params['result_type_id'];
		}
		$data['comment'] = $data['defect_ids'] = null;
		if(!empty($params['defect_ids']))
			$data['defect_ids'] = $params['defect_ids'];
		if(!empty($params['comment'])){
			$author = $this->userInfo->nickname;
			if(stripos($params['comment'], $author.':') !== false){
				$end = strlen($params['comment']) - (strlen($author) + 1);
				$start = strlen($author) + 1;
				$params['comment'] = substr($params['comment'], $start, $end);
				$comment = $data['comment'] = $author.":".$params['comment'];
			}
			else
				$comment = $data['comment'] = $author.":\n".$params['comment'];
		}
		if(!empty($params['new_issue_comment'])){
			$author = $this->userInfo->nickname;
			if(!empty($params['new_issue_comment']))
				$issue_comment = $data['issue_comment'] = $params['issue_comment']."\n".$author.":".date("Y-m-d H:i:s")."---".$params['new_issue_comment'];
			else
				$issue_comment = $data['issue_comment'] = $author.":".date("Y-m-d H:i:s")."---".$params['new_issue_comment'];
		}
		$finish_time = date("Y-m-d H:i:s");	
		if(!$params['result_type_id'])
			$finish_time = '0000-00-00 00:00:00';
		$res = $this->tool->query("SELECT id, name, creater_id, assistant_owner_id, tester_ids, group_id FROM cycle WHERE id = ".$params['parent']);
		$info = $res->fetch();
		$currentUser = $this->userInfo->id;
		$is_Tester = false;
		if(in_array($currentUser, explode(",", $info['tester_ids']))){
			$is_Tester = true;
		}
		$isAdmin = false;
		if(in_array('admin', $this->userInfo->roles))
			$isAdmin = true;
		
		//jira bug
		if(!empty($params['submit_bug']))
			$jira_result = $this->submitJiraBug($params);
		$i = 0;
		$c_f = json_decode($params['c_f'])[0];
		$res = $this->tool->query("SELECT id, result_type_id, tester_id, jira_key_ids, defect_ids FROM cycle_detail WHERE id in (".implode(',', $element).")");
		while($row = $res->fetch()){
			$where = "id=".$row['id'];
			$roles = array($info['creater_id'], $info['assistant_owner_id'], $row['tester_id']);
			if($isAdmin || in_array($currentUser, $roles) || ($row['tester_id'] == TESTER_DP && $is_Tester)){
				$defect_ids = '';
				if(!empty($jira_result)){
					$defect_ids = $data['defect_ids'];
					if(!empty($data['defect_ids'])){
						$row['defect_ids'] = $data['defect_ids'].",".$jira_result->key;
					}
					else{
						if(!empty($row['defect_ids'])){
							$row['defect_ids'] = $row['defect_ids'].",".$jira_result->key;
						}
						else{
							$row['defect_ids'] = $jira_result->key;
						}
					}
					$data['defect_ids'] = $row['defect_ids'];
				}
				$data['updater_id'] = $currentUser;
				$data['tester_id'] = $row['tester_id'];
				if(($isAdmin || $info['creater_id'] == $currentUser || $info['assistant_owner_id'] == $currentUser) && 0 == $row['tester_id'])
					$data['tester_id'] = $currentUser;
				$data['jira_key_ids'] = $data['defect_ids'];
				if($data['result_type_id'] != $row['result_type_id'])
					$data['finish_time'] = $finish_time;
				else if(!empty($data['finish_time']))
					unset($data['finish_time']);
				$this->tool->update('cycle_detail', $data, $where);	
				if(!empty($defect_ids))
					$data['defect_ids'] = $defect_ids;
				if($params['result_type_id'] && $data['result_type_id'] != $row['result_type_id'])
					$this->tool->updatelastresult($row['id']);
				$i++;
			}
		}

		if($i == count($element)){
			//$params['id'] = json_decode($params['id']);
			$tester = $data['tester_id']; 
			if(!empty($data['finish_time']))
				$finish_time = $data['finish_time'];
			$data = array('id'=>$params['id'][0], 'result_type_id'=>$params['result_type_id'], 'test_env_id'=>$params['test_env_id'],
				'comment'=>'null', 'issue_comment'=>'null', 'defect_ids'=>'null', 'jira_key_ids'=>'null', 'logs'=>'');
			if(!empty($comment))
				$data['comment'] = $comment;
			if(!empty($issue_comment))
				$data['issue_comment'] = $issue_comment;
			if(!empty($finish_time))
				$data['$finish_time'] = $finish_time;
			// if(!empty($params['defect_ids']))
				// $data['defect_ids'] = $params['defect_ids'];
			$res = $this->tool->query("SELECT id, tester_id, jira_key_ids, defect_ids, logs FROM cycle_detail WHERE id = ".$params['id'][0]);
			if($record = $res->fetch()){
				// if('zzvw_cycle_detail_stream' == $this->params['table'])
					// $record['logFile'] = $record['stream_logFile'];
				// if(!empty($record['logFile'])){
					// $data['logFile'] = $record['logFile'];
				// }
				$record['logs'] = json_decode($record['logs']);
				if(!empty($record['logs'])){
					$logFileList = array();
					foreach($record['logs'] as $key=>$fileInfo){
						if(empty($fileInfo))
							continue;
						$res = $this->tool->query("SELECT server, directory, is_url FROM log_key WHERE id={$key} LIMIT 1");
						if($loginfo = $res->fetch()){
							foreach($fileInfo as $filename){
								if(stripos("/", $filename) === 0 || stripos("\\", $filename) === 0)
									$filename = substr($filename, 0, 1);
								switch($loginfo["server"]){
									case "umbrella":
										$logFileList[] = $filename.",".$loginfo['is_url'].",".basename($filename);
										break;
									case "dapeng":
										$logFileList[] = "http://".$loginfo["server"]."/".$loginfo["directory"]."/".$filename."/None/None/None/".",".$loginfo['is_url'].",Dapeng Log";
										break;
									default:
										$logFileList[] = "http://".$loginfo["server"]."/".$loginfo["directory"]."/".$filename.",".$loginfo['is_url'].",".basename($filename);
										break;
								}
							}
						}
						else
							continue;
					}
					$data['logs'] = implode(";", $logFileList);
				}
				else
					$data['logs'] = "";
				
				$data['jira_key_ids'] = $record['jira_key_ids'];
				$data['defect_ids'] = $record['defect_ids'];
			}
			$data['updater_id'] = $this->userInfo->id;
			$data['tester_id'] = $tester;
			$data['group_id'] = $info['group_id'];
			return $this->returnData($data);
		}
	}
	
	protected function returnData($data){
		$groups = array(GROUP_KSDK, GROUP_USB);
		if(!in_array($data['group_id'], $groups))
			$datas['statistics'] = $this->getStatistics();
		unset($data['group_id']);
		$datas['data'] = $data;
		return json_encode($datas);
	}
	
	private function submitJiraBug($params){
		$username = $params['submit_username']; 
		$password = $params['submit_password']; 
		$basecurl = 'http://sw-jira.freescale.net/rest/api/2';//根地址http://sw-jira.freescale.net/login.jsp
		$url = $basecurl.'/issue/';//要采集的页面地址
				
		if(empty($params['jira_environment'])){
			if($params['table'] != 'zzvw_cycle_detail'){
				$res_codec = $this->tool->query("select * from cycle_detail left join codec_stream on codec_stream.id = cycle_detail.codec_stream_id
					where cycle_detail.id=".$params['id'][0]);
				if($row = $res_codec->fetch()){
					$testcase = $row;
					$res = $this->tool->query("select env_item_ids from test_env where id=".$params['test_env_id']);
		// print_r($row['test_env_id']."zzz");
					if($info = $res->fetch()){
						$env_item = false;
						if(!empty($info['env_item_ids'])){
							$res0 = $this->tool->query("select steps, precondition, command from stream_steps".
								" where codec_stream_type_id=".$row['codec_stream_type_id']." and env_item_id in (".$info['env_item_ids'].")");
							if($info0 = $res0->fetch()){		
								$env_item = true;
								$testcases = $info0;
							}
						}
						if(!$env_item){
							$result = $this->tool->query("select os.name from prj left join os on prj.os_id = os.id where prj.id = ".$row['prj_id']);
							$os = $result->fetch();
							if(stripos(strtolower($os['name']), "android") !== false)
								$os_name = 'Android';
							else if(stripos(strtolower($os['name']), "linux") !== false)
								$os_name = 'Linux';
							if(!empty($os_name)){
								$res1 = $this->tool->query("select steps.steps, steps.precondition, steps.command from stream_steps steps".
									" left join stream_tools tools on steps.env_item_id = tools.env_item_id".
									" where tools.codec_stream_format_id=".$row['codec_stream_format_id']." and tools.os='".$os_name."'");
								if($info1 = $res1->fetch()){
									$testcases = $info1;
								}
							}
						}
					}
				}
			}
			else{
				$res = $this->tool->query("select * from cycle_detail left join testcase_ver on testcase_ver.id = cycle_detail.testcase_ver_id where cycle_detail.id = ".$params['id'][0]);
				$testcase = $res->fetch();
			}
		}
		else{
			$testcase['precondition'] = $params['jira_environment'];
		}
		// //test database
		// $postData = '{"fields":{"project":{"id":"'.$params['jira_project'].'"}, "summary":"'.$params['jira_summary'].'", "description":"'.$params['jira_description'].'", '.
			// ' "issuetype":{"name":"Bug","subtask":"0"}, "priority":{"id": "'.$params['jira_priority'].'"}, "components":[{"id":"'.$params['jira_components'].'"}],'.
			// ' "environment":"'.mysql_escape_string($testcase['precondition']).'", "customfield_10441":{"name": "'.$username.'"}, "customfield_10449":{"id":"10464"}'.
			// //10449--test/validation: 10464
			// // visions,attchment, cq_link, cq_id, regression
			// //"versions":[{"id":""}],"attchment", "customfield_10404", "customfield_10424", "customfield_10441", "customfield_10460"'
			// '}}';
// print_r($postData);
		//offical	
		$postData = array('fields'=>array('project'=>array('id'=>$params['jira_project']), 'summary'=>$params['jira_summary'], 'description'=>$params['jira_description'],
			'issuetype'=>array('name'=>'Bug', 'subtask'=>0), 'priority'=>array('id'=>$params['jira_priority']), 'environment'=>$testcase['precondition'],
			'customfield_10604'=>array('name'=>$username)
			));
		// '{"fields":{"project":{"id":"'..'"}, "summary":"'.$params['jira_summary'].'", "description":"'..'", '.
			// '"issuetype":{"name":"Bug","subtask":"0"},"priority":{"id": "'.$params['jira_priority'].'"}, '.
			// '"environment":"'.mysql_escape_string($testcase['precondition']).'", "customfield_10604":{"name": "'.$username.'"}';
		if(!empty($params['jira_customfield_10403']))//reproducibility
			$postData['fields']['customfield_10403'] = array('id'=>$params['jira_customfield_10403']);
		if(!empty($params['jira_customfield_10404']))//reporter
			$postData['fields']['customfield_10404'] = array('id'=>$params['jira_customfield_10404']);// $postData .= ', "customfield_10403":{"id":"'.$params['jira_customfield_10403'].'"}';
		if(!empty($params['jira_customfield_10300'])){//board
			$boards = array();
			if(is_array($params['jira_customfield_10300'])){
				foreach($params['jira_customfield_10300'] as $val){
					$res = $this->tool->query("select jira_boardsId from jira_boards where id = $val");
					if($row = $res->fetch()){
							$boards[] = array('id'=>$row['jira_boardsId']);// $str = '[{"id":"'.$row['jira_boardsId'].'"}';
					}
				}
			}
			else
				$boards[] = array('id'=>$params['jira_customfield_10300']);
			$postData['fields']['customfield_10300'] = $boards;
		}
		if(!empty($params['jira_customfield_10301'])){//board
			$tool_chains = array();
			if(is_array($params['jira_customfield_10301'])){
				foreach($params['jira_customfield_10301'] as $val){
					$res = $this->tool->query("select jira_tool_chainsId from jira_tool_chains where id = $val");
					if($row = $res->fetch()){
							$tool_chains[] = array('id'=>$row['jira_tool_chainsId']);// $str = '[{"id":"'.$row['jira_boardsId'].'"}';
					}
				}
			}
			else
				$tool_chains[] = array('id'=>$params['jira_customfield_10301']);
			$postData['fields']['customfield_10301'] = $tool_chains;
		}
		if(!empty($params['jira_security']))
			$postData['fields']['security'] = array('id'=>$params['jira_security']);//$postData .= ', "security":{"id":"'.$params['jira_security'].'"}';
		if(!empty($params['jira_versions']))
			$postData['fields']['versions'] = array(array('id'=>$params['jira_versions']));//$postData .= ', "versions":[{"id":"'.$params['jira_versions'].'"}]';
		if(!empty($params['jira_components']))
			$postData['fields']['components'] = array(array('id'=>$params['jira_components']));//$postData .= ', "components":[{"id":"'.$params['jira_components'].'"}]';
		if(!empty($params['jira_labels']))
			$postData['fields']['labels'] = array($params['jira_labels']);//$postData .= ', "labels":["'.$params['jira_labels'].'"]';
		$headers = array( 
			"Accept: application/json",  
			"Content-Type: application/json"  
		);	
		$curl_params = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false, //为什么有httpheader但是header确实false？
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_USERPWD => "$username:$password",
			CURLOPT_POSTFIELDS => json_encode($postData),
			CURLOPT_VERBOSE => true, //如果设为true, 会打印所有过程信息
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			
		) ; 

		$ch = curl_init();  
		curl_setopt_array($ch, $curl_params);  
		$results = curl_exec($ch); 
		$ch_error = curl_error($ch); 
		curl_close($ch);

		if ($ch_error) { 
			print_r("~~~~~~~~~~~~~~~~~~~~~~"); 
			echo "cURL Error: $ch_error"; 
		} else { 
			$result = json_decode($results);
// print_r($result);
// return;				
			if(!empty($result->id)){
				if(!empty($params['logfile']) && !empty($params['logfile_path'])){
					if(file_exists($params['logfile_path'])){
						$attchment_url = $basecurl.'/issue/'.$result->id.'/attachments';//$result->self.'/attachments';
						$curl_params[CURLOPT_URL] = $attchment_url;
						$curl_params[CURLOPT_HTTPHEADER] = array("X-Atlassian-Token: nocheck", "Content-Type: multipart/form-data" );
						$curl_params[CURLOPT_POSTFIELDS] = array("file"=>"@".$params['logfile_path'].";filename=".$params['logfile']);
						
						$ch = curl_init();  
						curl_setopt_array($ch, $curl_params);  
						$res = curl_exec($ch); 
						$ch_error = curl_error($ch); 
						curl_close($ch);
					}
				}
				if(!empty($params['jira_watchers'])){
					if(is_array($params['jira_watchers']))
						$params['jira_watchers'] = implode(",", $params['jira_watchers']);
					$useradmin = dbFactory::get('useradmin');
					$res0 = $useradmin->query("select group_concat( distinct username) as username from users where id in ({$params['jira_watchers']})");
					if($row0 = $res0->fetch()){
						$watchers_url = $basecurl.'/issue/'.$result->id.'/watchers/';
//print_r($watchers_url);
						$curl_params[CURLOPT_URL] = $watchers_url;
						$curl_params[CURLOPT_HTTPHEADER] = array("Accept: application/json", "Content-Type: application/json");
						$curl_params[CURLOPT_POSTFIELDS] = json_encode($row0['username']);//"[{name:a},{name:b},{name:c}]"
//print_r($curl_params);
// return;
						$ch = curl_init();  
						curl_setopt_array($ch, $curl_params);  
						$res = curl_exec($ch); 
//print_r($res);
						$ch_error = curl_error($ch); 
						curl_close($ch);
					}
					
				}
			}
		}
		if(!empty($result) && !empty($result->id))
			return $result;
		else 
			return null;
	}
	
}

?>