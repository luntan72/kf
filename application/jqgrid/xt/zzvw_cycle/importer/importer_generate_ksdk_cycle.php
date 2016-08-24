<?php
/*
从一个Codec的Excel文件中导入数据，包括Case数据、Stream数据、Trick数据和测试结果数据
*/

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_cycle.php');

class xt_zzvw_cycle_importer_generate_ksdk_cycle extends xt_zzvw_cycle_importer_cycle{
	
	protected function process(){
		print_r(" Start to process......\n<BR />");
// print_r($this->parse_result);
		foreach($this->parse_result as $title=>$sheet_data){
			print_r("Processing sheet $title...\n<BR />");
			switch($title){
				case 'Cover':
					$this->processCycle($title, $sheet_data);
					break;
				default:
					$this->processSheetData($title, $sheet_data);
					break;
			}
			unset($this->parse_result[$title]);
		}
		$res = $this->tool->query("select group_concat(distinct detail.prj_id) as prj_ids, group_concat(distinct detail.compiler_id) as compiler_ids, 
			group_concat(distinct detail.build_target_id) as build_target_ids, group_concat(distinct testcase.testcase_type_id) as testcase_type_ids,
			group_concat(distinct detail.tester_id) as tester_ids
			from cycle_detail detail left join testcase on testcase.id = detail.testcase_id where detail.cycle_id = {$this->params['cycle_id']}");
		if($row = $res->fetch()){
			$this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'tester_ids'=>$row['tester_ids'],
				'build_target_ids'=>$row['build_target_ids'], 'testcase_type_ids'=>$row['testcase_type_ids']), "id={$this->params['cycle_id']}");
		}
		print_r("\nNow you can close the dialog\n");
	}
	
	protected function processCycle($title, $sheet_data){
		$cycle_name = $sheet_data['cycle_name']."_".$sheet_data['release'];
// print_r($sheet_data['end_date']);
// print_r("\n<br />");
		$sheet_data['end_date'] = $this->excelTime($sheet_data['end_date']);
// print_r($sheet_data['end_date']);
// print_r("\n<br />");
		$sheet_data['start_date'] = $this->excelTime($sheet_data['start_date']);
		$this->params['finish_time'] = $sheet_data['end_date'];
		$this->params['rel_id'] = $this->tool->getElementId('rel', array('name'=>$sheet_data['release']), array('name'));
// print_r($cycle_name);
// print_r("\n<br />");
		$data = array('name'=>$cycle_name, 'start_date'=>$sheet_data['start_date'], 'end_date'=>$sheet_data['end_date'],
			'creater_id'=>$this->params['owner_id'], 'rel_id'=>$this->params['rel_id'], 'group_id'=>GROUP_KSDK);//ksdk
		$this->params['cycle_id'] = $this->tool->getElementId('cycle', $data, array('name'));
	}
	
	protected function processCycleDetail($case, $caseInfo){
		static $testers = array();
		$rel_id = $this->params['rel_id'];
		
		$cycleInfo = array();
		$cycleInfo['build_result_id'] = RESULT_TYPE_BLANK;
		if($case['build_result'])
			$cycleInfo['build_result_id'] = $this->tool->getResultId($case['build_result']);
		$cycleInfo['result_type_id'] = RESULT_TYPE_BLANK;
		if($case['result_type']){
			$cycleInfo['result_type_id'] = $this->tool->getResultId($case['result_type']);
			if($case['result_type'] == 'ongoing')
				$cycleInfo['result_type_id'] = RESULT_TYPE_BLANK;
		}
		$last_run = date('Y-m-d H:i:s');
		if(!empty($this->params['finish_time']))
			$last_run = $this->excelTime($this->params['finish_time']);
		$cycleInfo['build_target_id'] = $this->tool->getExistedId("build_target", array('name'=>$case['build_target']), array('name'));
		$cycleInfo['compiler_id'] = $this->tool->getExistedId("compiler", array('name'=>$case['compiler']), array('name'));
		$cycleInfo['tester_id'] = TESTER_BLANK;
		if(empty($testers[$case['tester']])){
			$useradmin = dbFactory::get('useradmin');
			$res = $useradmin->query("select * from users where username = '".$case['tester']."'");
			if($row = $res->fetch())
				$testers[$case['tester']] = $row['id'];
		}
		if(!empty($testers[$case['tester']]))
			$cycleInfo['tester_id'] = $testers[$case['tester']];
		$cycleInfo['jira_key_ids'] = null;
		if(preg_match("/^(.*?-\d{2,})(.*)/i", $case['jiraInfo'], $matches)){
			$cycleInfo['jira_key_ids'] = trim($matches[1]);
			$cycleInfo['jira_key_ids'] = str_replace(" ", "", $cycleInfo['jira_key_ids']);
			$cycleInfo['jira_key_ids'] = str_replace(",", ";", $cycleInfo['jira_key_ids']);
// print_r($cycleInfo['jira_key_ids']);
// print_r("\n<br />");		
		}
		// return;
		
		// if(!empty($case['jiraInfo'])){
			// if(empty($case['comment']))
				// $case['comment'] = $case['jiraInfo'];
			// else
				// $case['comment'] = $case['comment']."\n".$case['jiraInfo'];
		// }
		if(empty($case['comment']) || '' == $case['comment'])
			$case['comment'] = null;
			
		$cycle_detail = array('cycle_id'=>$this->params['cycle_id'], 'testcase_id'=>$caseInfo['testcase_id'], 'testcase_ver_id'=>$caseInfo['testcase_ver_id'], 
			'build_result_id'=>$cycleInfo['result_type_id'], 'result_type_id'=>$cycleInfo['result_type_id'] ,'jira_key_ids'=>$cycleInfo['jira_key_ids'], 
			'comment'=>$case['comment'], 'finish_time'=>$last_run, 'codec_stream_id'=>0, 'test_env_id'=>1, 'prj_id'=>$caseInfo['prj_id'], 
			'build_target_id'=>$cycleInfo['build_target_id'], 'compiler_id'=>$cycleInfo['compiler_id'], 'tester_id'=>$cycleInfo['tester_id']
		);
// print_r($cycle_detail);
		$keys = array('cycle_id', 'testcase_id', 'test_env_id', 'prj_id', 'compiler_id', 'build_target_id', 'codec_stream_id');
		$cycle_detail_id = $this->tool->getElementId('cycle_detail', $cycle_detail, $keys);
		
		// 更新testcase_last_result
		$res = $this->tool->query("select * from testcase_last_result WHERE testcase_id={$caseInfo['testcase_id']} and rel_id={$this->params['rel_id']} and prj_id={$caseInfo['prj_id']}");
		if ($row = $res->fetch()){
			if ($row['tested'] < $last_run){
				$data = array('cycle_detail_id'=>$cycle_detail_id, 'result_type_id'=>$cycle_detail['result_type_id'], 'tested'=>$last_run);
				$this->tool->update('testcase_last_result', $data, "id=".$row['id']);
			}
		}
		else{
			$data = array('testcase_id'=>$caseInfo['testcase_id'], 'rel_id'=>$this->params['rel_id'], 'prj_id'=>$caseInfo['prj_id'], 'cycle_detail_id'=>$cycle_detail_id, 'result_type_id'=>$cycle_detail['result_type_id'], 'tested'=>$last_run);
			$this->tool->insert('testcase_last_result', $data);
		}
		// 更新testcase.last_run
		$res = $this->tool->query("select * from testcase where id={$caseInfo['testcase_id']}");
		$row = $res->fetch();
//print_r("finish_time = $finish_time, last_run={$row['last_run']}, case_id={$caseInfo['testcase_id']}\n");
		if ($row['last_run'] < $last_run){
			$this->tool->update('testcase', array('last_run'=>$last_run), 'id='.$caseInfo['testcase_id']);
		}
		return $cycle_detail_id;
	}
	
	// protected function processCompilerVersion($title, $sheet_data){
// print_r($sheet_data['compilers']);
		// if(preg_match('/\r(.*)\r.*$/i', $sheet_data['compilers'], $matches)){
			// //if(preg_match("/^2.()$/i", $matches[1]))
// print_r($matches);		
		// }
	// }
	
	protected function processSheetData($title, $sheet_data){
print_r('title:'.$title."  "."\n<br />");
		foreach($sheet_data as $row=>$case){
			if (empty($case['code']))
				continue;
			$case['testcase_type'] = $title;
			if(empty($case['testcase_testpoint'])){
				$case['testcase_testpoint'] = 'Default Testpoint For '.$case['testcase_module'];
			}
			$caseinfo = $this->processCase($case);
print_r($caseinfo);
print_r("\n<br />");
			if(!empty($caseinfo)){
				$this->processPrjCaseVer($case, $verInfo);
				$cycle_detail_id = $this->processCycleDetail($case, $caseinfo);
			}
		}
		// print_r("\n<BR /> Finished to upload the sheet: $title");
	}
	
	protected function processCase($case){
		if(preg_match("/^(.*)_(freertos|mqx|ucosii|ucosiii)$/i", $case['summary'], $matches)){
			$case['summary'] = $matches[1];
		}
		else if(preg_match("/^(.*)_with_(freertos|mqx|ucosii|ucosiii)$/i", $case['summary'], $matches)){
			$case['summary'] = $matches[1];
		}
		$res = $this->tool->query("select * from testcase where summary = '{$case['summary']}' and testcase_type_id = 17 and isactive = ".ISACTIVE_ACTIVE);
		if($row = $res->fetch()){
			$testcase_id = $row['id'];
		}
		else
			return array();
		if(empty($info['os'][$case['os']]))
			$info['os'][$case['os']] = $this->tool->getElementId("os", array('name'=>'KSDK_'.$case['os']), array('name'));
		$case['os_id'] = $info['os'][$case['os']];
		$ver = array('testcase_id'=>$testcase_id);
		if(empty($info['prj'][$case['platform']."_".$case['os']])){
			$res = $this->tool->query("select name from board_type where isactive = ".ISACTIVE_ACTIVE);
			while($row = $res->fetch())
				$board_type[] = strtoupper($row['name']);
			$board_type[] = 'SDB';
			$board_type = implode("|", $board_type);
			if(preg_match("/^(".$board_type.")(.*)$/i", strtolower($case['platform']), $matches)){//PSDK
				$board_type_id = $this->tool->getElementId("board_type", array('name'=>strtoupper(trim($matches[1]))), array('name'));
				$chip = trim($matches[2]);
				// if(preg_match("/^(.*)(128r|256r|512r|k02|kv30|512|1m)$/i", $chip, $mt))
					// $chip = strtoupper(trim($mt[1]))."_".trim($mt[2]);
				// else
					// $chip = strtoupper($chip);
				$chip = strtoupper($chip);
				//$os_id = $this->tool->getElementId('os', array('name'=>$default_os));
				$chip_id = $this->tool->getChipId($chip, array("os_id"=$case['os_id'], "board_type_id"=>$board_type_id));
				$name = $chip."-".strtoupper(trim($matches[1]))."-KSDK_".strtolower($case['os']);
// print_r('name: '.$name);
// print_r("\n<br />");
				$info['prj'][$case['platform']."_".$case['os']] = $this->tool->getElementId("prj", array('name'=>$name, 'os_id'=>$info['os'][$case['os']], 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id), 
					array('os_id', 'chip_id', 'board_type_id'));
			}
		}
		$ver['prj_id'] = 0;
		if(!empty($info['prj'][$case['platform']."_".$case['os']])){
			$ver['prj_id'] = $info['prj'][$case['platform']."_".$case['os']];
// print_r($case['platform']." : ".$ver['prj_id']);
// print_r("\n<br />");
			$prj_id = $ver['prj_id'];
			$rel_id = $this->params['rel_id'];
			$link['prj_id'] = $prj_id;
			$history = array('prj_id'=>$prj_id, 'testcase_id'=>$ver['testcase_id'], 'act'=>'remove');
			$res0 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
				" LEFT JOIN testcase_ver ON testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
				" LEFT JOIN testcase ON testcase.id = prj_testcase_ver.testcase_id".
				" WHERE prj_testcase_ver.prj_id=$prj_id".
				" AND prj_testcase_ver.testcase_id={$ver['testcase_id']}".
				" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")".
				" AND testcase.isactive = ".ISACTIVE_ACTIVE." LIMIT 1");
			if($row0 = $res0->fetch()){
				$ver['testcase_ver_id'] = $row0['testcase_ver_id'];
				return $ver;
			}
			else{
				$res1 = $this->tool->query("SELECT testcase_ver.id as testcase_ver_id FROM testcase_ver LEFT JOIN testcase on testcase.id = testcase_ver.testcase_id".
					" WHERE testcase_id={$ver['testcase_id']}".
					" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")".
					" AND testcase.isactive = ".ISACTIVE_ACTIVE." ORDER BY testcase_ver.id DESC");
				while($row1 = $res1->fetch()){
					$ver['testcase_ver_id'] = $row1['testcase_ver_id'];
					return $ver;
				}
			}
		}
		return array();
	}
	
	protected function analyze_cover($sheet, $title){
		$this->analyze_ksdk($sheet, $title);
	}
	
	protected function analyze_test_case_list($sheet, $title){
		$this->analyze_ksdk($sheet, $title);
	}
	
	private function analyze_ksdk($sheet, $title){
		$highestColumn = $sheet->getHighestColumn(); // e.g 'F'
 // print_r("title = $title, hiColumn = $highestColumn, highestRow = $highestRow\n");		
		$cm = $this->getColumnMap($title, $highestColumn);
		if (!empty($cm)){
			foreach($cm['columns'] as $key=>$row_col){
				$rc = explode(",", $row_col);
				$this->parse_result[$title][$key] = trim($this->getCell($sheet, $rc[0], $rc[1]));
			}
		}
	}
}
?>