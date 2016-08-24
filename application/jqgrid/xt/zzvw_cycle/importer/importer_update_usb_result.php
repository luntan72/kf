<?php
/*
从一个Codec的Excel文件中导入数据，包括Case数据、Stream数据、Trick数据和测试结果数据
*/

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_cycle.php');

class xt_zzvw_cycle_importer_update_usb_result extends xt_zzvw_cycle_importer_cycle{
	
	protected function processSheetData($title, $sheet_data){
		$total = 0;
		$no_update = 0;
		$update = 0;
		$testers = array();
		$useradmin = dbfactory::get("useradmin");
		foreach($sheet_data as $resultInfo){
			$data = array();
			if(!isset($tester[$resultInfo['tester']])){
				$res = $useradmin->query("select id from users where username = '".$resultInfo['tester']."'");
				if($row = $res->fetch())
					$tester[$resultInfo['tester']] = $tester_id = $row['id'];
				else{
					print_r("tester: {$resultInfo['tester']} does not exits");
					continue;
				}
			}
			else
				$tester_id = $tester[$resultInfo['tester']];
			if($tester_id != $this->params['owner_id'] && $tester_id != TESTER_FPT_USB){	
				print_r("tester {$this->params['owner_id']} is not owner or ftp_usb"."\n<br />");
				continue;
			}	
			$prj_id = $this->tool->getExistedId('prj', array('name'=>$resultInfo['prj']), array('name'));
			if($prj_id == 'error'){
				print_r("{$resultInfo['prj']} is not exits"."\n<br />");
				continue;
			}
			$testcase_id = $this->tool->getExistedId('testcase', array('code'=>$resultInfo['code']), array('code'));
			if($testcase_id == 'error'){
				print_r("{$resultInfo['code']} is not exits"."\n<br />");
				continue;
			}
			$result_type_id = $this->tool->getResultId($resultInfo['result']);
			if($result_type_id == 'error'){
				print_r("result of {$resultInfo['code']} under {$resultInfo['prj']} is not exits"."\n<br />");
				continue;
			}
			// print_r(("".$build_result_id=='error')."abc\n<br>");
			$build_result_id = $this->tool->getResultId($resultInfo['build_result']);
			if($resultInfo['build_result'] == '0')
				$build_result_id = RESULT_TYPE_BLANK ;
			if('error' == "".$build_result_id){
				print_r("buidl result of {$resultInfo['code']} under {$resultInfo['prj']} is not exits");
				continue;
			}
			$compiler_id = $this->tool->getExistedId('compiler', array('name'=>$resultInfo['compiler']), array('name'));
			if($compiler_id == 'error')
				continue;
			$build_target_id = $this->tool->getExistedId('build_target', array('name'=>$resultInfo['build_target']), array('name'));
			if($build_target_id == 'error')
				continue;
			$test_env_id = $this->tool->getExistedId('test_env', array('name'=>$resultInfo['env']), array('name'));
			if($test_env_id == 'error')
				continue;
			//defect_ids已经有了怎么办，comment已有了怎么办
			if(empty($resultInfo['comment']))
				$resultInfo['comment'] = null;
			if(empty($resultInfo['defect_ids']))
				$resultInfo['defect_ids'] = null;
			$sql = "SELECT * FROM cycle_detail WHERE cycle_id={$this->params['id']} AND prj_id = $prj_id AND testcase_id = $testcase_id AND test_env_id = $test_env_id".
				" AND compiler_id = $compiler_id AND build_target_id = $build_target_id AND codec_stream_id = 0";
			// remove below as tina suggested
			// if($tester_id != TESTER_FPT_USB)
				 // $sql .= " AND tester_id={$tester_id}"; 
			 $sql .= " LIMIT 1";
			//print_r($sql."\n<br>");
			$res = $this->tool->query($sql);
			if($info = $res->fetch()){
				// if($row['result_type_id'] != $result_type_id){
					$data = array('result_type_id'=>$result_type_id, 'build_result_id'=>$build_result_id, 'comment'=>$resultInfo['comment'], 
						'defect_ids'=>$resultInfo['defect_ids'], 'jira_key_ids'=>$resultInfo['defect_ids'], 'finish_time'=>date('Y-m-d H:i:s'));
					// if($tester_id == TESTER_FPT_USB)
						// $data['tester_id'] = TESTER_FPT_USB;
					// remove above and chang to below as tina suggested
					$data['tester_id'] = $tester_id;
					$this->tool->update("cycle_detail", $data, "id=".$info['id']);
					$this->tool->updatelastresult($info['id']);
					
					$update ++ ;
				// }
				// else
					// $no_update ++ ;
			}
			else{
				print_r("testcase:".$resultInfo['code']."---");
				print_r('prj:'.$resultInfo['prj']."---");
				print_r('target:'.$resultInfo['build_target']."---");
				print_r('ide:'.$resultInfo['compiler']."---");
				print_r('env:'.$resultInfo['env']."---");
				print_r("\n<br />");
			}
		}
		if($update)
print_r("\n<br />update $update results!!!");
		if($no_update)
print_r("\n<br />$no_update cases result same with the document!!!");
		if(empty($update) && empty($no_update))
print_r("No update here!"."\n<br />" );	
	}
}
?>