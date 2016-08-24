<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_get_resultinfo.php');

class xt_zzvw_cycle_detail_stream_action_get_resultinfo extends xt_zzvw_cycle_detail_action_get_resultinfo{

	public function handlePost(){
		$params = $this->params;
		if (!empty($params['id'])){	
			$sql = "SELECT id, d_code, codec_stream_id, cycle_id, tester_id, test_env_id, logs FROM zzvw_cycle_detail_stream WHERE id=".$params['id'];
			$res = $this->tool->query($sql);
			$detail = $res->fetch();
			// $logFileList = array();
			// if(!empty($detail['logs'])){
				// foreach(json_decode($detail['logs'], true) as $key=>$fileInfo){
					// $res = $this->tool->query("SELECT server, directory, is_url FROM log_key WHERE id={$key} LIMIT 1");
					// if($row = $res->fetch()){
						// foreach($fileInfo as $filename){
							// switch($row["server"]){
								// case "umbrella":
									// $path = LOG_ROOT."/".$params['parent']."/".$params['id']."/stream";//$path = $main_path."/".$detail['d_code']."_".$params['id'];
									// $path = $this->tool->uniformFileName($path);
									// $tmp = $this->tool->uniformFileName($path."/".$filename);
									// if(file_exists($tmp))
										// $logFileList[$row['is_url']][basename($filename)] = $tmp;
									// break;
								// case "dapeng":
									// $logFileList[$row['is_url']]["Dapeng Log"] = "http://".$row["server"]."/".$row["directory"]."/".$filename."/None/None/None/";
									// break;
								// case "10.192.225.195":
									// $logFileList[$row['is_url']][basename($filename)] = "http://".$row["server"]."/".$row["directory"].$filename;
									// break;
							// }
						// }
					// }
					// else
						// continue;
				// }
			// }

			if($detail){	
				// if($params['result_type_id'] == RESULT_TYPE_FAIL){
					// $res = $this->tool->query("select comment, defect_ids, result_type_id from zzvw_cycle_detail_stream where cycle_id = ".$detail['cycle_id']." and codec_stream_id=".$detail['codec_stream_id']);
					// while($info = $res->fetch()){
						// $d['result_type_id'][] = $info['result_type_id'];
						// if(!empty($info['comment']) && $info['comment'] != '')
							// $d['comment'][] = $info['comment'];
						// if(!empty($info['defect_ids']) && $info['defect_ids'] != '')
							// $d['defect_ids'][] = $info['defect_ids'];
					// }
				// }
				$res = $this->tool->query("SELECT id, name FROM result_type");
				$results['-1'] = '==blank==';
				while($result = $res->fetch())
					$results[$result['id']] = $result['name'];				
				$res = $this->tool->query("SELECT id, name FROM test_env");
				//$envs[0] = '';
				while($env = $res->fetch())
					$envs[$env['id']] = $env['name'];					
				// $format = array('', 'txt', 'excel', 'yml', 'html', 'zip');
				$cols = array(
					array('id'=>'code', 'name'=>'code', 'label'=>'Stream', 'editable'=>false, 'DATA_TYPE'=>'text', 'type'=>'text', 'defval'=>$detail['d_code']),
					array('id'=>'test_env_id', 'name'=>'test_env_id', 'label'=>'Test Env', 'editable'=>true, 'DATA_TYPE'=>'int', 'type'=>'select', 'defval'=>$detail['test_env_id'], 'editoptions'=>array('value'=>$envs), 'editrules'=>array('required'=>true)),
					array('id'=>'result_type_id', 'name'=>'result_type_id', 'label'=>'result', 'editable'=>true, 'DATA_TYPE'=>'int', 'type'=>'select', 'defval'=>$params['result_type_id'],'editoptions'=>array('value'=>$results), 'editrules'=>array('required'=>true)),
					array('id'=>'comment', 'name'=>'comment', 'label'=>'Comment', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'textarea'),
					array('id'=>'defect_ids', 'name'=>'defect_ids', 'label'=>'CRID/JIRA Key', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'text'),
					//array('id'=>'file_format', 'name'=>'file_format', 'label'=>'Log Foramt', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'select', 'editoptions'=>array('value'=>$format)),
					// array('id'=>'logfile', 'name'=>'logfile', 'label'=>'Logfile', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'file', 'editoptions'=>array('value'=>$logFileList)),
					array('id'=>'issue_comment', 'name'=>'issue_comment', 'label'=>'Issue Comment', 'editable'=>false, 'DATA_TYPE'=>'text', 'type'=>'textarea'),
					array('id'=>'new_issue_comment', 'name'=>'new_issue_comment', 'label'=>'New Issue Comment', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'textarea'),
					array('name'=>'submit_username', 'label'=>'JIRA User', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'text', 'defval'=>$this->userInfo->username),
					array('name'=>'submit_password', 'label'=>'JIRA PWD', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'password'),
					array('id'=>'submit_bug', 'name'=>'submit_bug', 'label'=>'submit To JIRA', 'editable'=>true, 'DATA_TYPE'=>'int', 'type'=>'checkbox', 
						'editoptions'=>array('value'=>array(1=>'submit'))),
				);
				$btn = true;
				$this->renderView('newElement.phtml', array('cols'=>$cols, 'id'=>$params['id'], 'btn'=>$btn), '/jqgrid/xt/'.$this->get('table')."/view");
			}
		}
	}
}

?>