<?php
/*
从一个Codec的Excel文件中导入数据，包括Case数据、Stream数据、Trick数据和测试结果数据
*/

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_cycle.php');

class xt_zzvw_cycle_importer_process_linux_function extends xt_zzvw_cycle_importer_cycle{
	
	protected function process(){
		print_r(" Start to process......\n<BR />");
// print_r($this->parse_result);
		$ret1 = $this->process_cycle_info('cycle info', $this->parse_result['cycle info']);
		$ret2 = $this->process_cycle_plan('Test plan', $this->parse_result['Test plan']);
		$ret3 = $this->process_function('Testcase', $this->parse_result['Testcase']);	
	}
	
	protected function default_analyze_sheet($sheet, $title){
		$titles = array('cycle info', 'Testcase', 'Test plan');
		if(in_array($title, $titles)){
			parent::default_analyze_sheet($sheet, $title);
		}
	}
	
	protected function process_cycle_info($title, $sheet_data){
		//process cycle
		$this->params['useradmin'] = dbfactory::get('useradmin');
		$week = date("W");
		$year = date('y');
		$date = date("Y-m-d");
		$ret = "";
		foreach($sheet_data as $cycleinfo){
			$creater_id = $tester_ids = 0;
			$testers = array();
			$os = $cycleinfo['os'];
			if(preg_match('/^(.*)-(.*)-(.*)$/i', strtoupper($cycleinfo['platform']), $matches)){
				$board_type_id = $this->tool->getElementId("board_type", array('name'=>strtoupper(trim($matches[2]))), array('name'));
				$chip = trim($matches[1]);
				$chip = lcfirst(strtoupper($chip));
				
				if(empty($os))
					$os = trim($matches[3]);	
				if(empty($os))
					continue;
				
				$os_id = $this->tool->getElementId('os', array('name'=>$os), array('name'));
				$this->tool->getElementId('os_testcase_type', array('os_id'=>$os_id, 'testcase_type_id'=>$this->params['testcase_type_ids']));
				$this->tool->getElementId('groups_os', array('os_id'=>$os_id, 'group_id'=>$this->params['group_id']));
				$chip_id = $this->tool->getChipId($chip, array("os_id"=>$os_id, "board_type_id"=>$board_type_id));
				$name = $chip."-".strtoupper(trim($matches[2]))."-".ucwords($os);
				$prj_id = $this->tool->getElementId("prj", array('name'=>$name, 'os_id'=>$os_id, 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id), 
					array('os_id', 'chip_id', 'board_type_id'));
					
				if(empty($cycleinfo['release']))
					continue;
				
				$rel_id = $this->tool->getElementId("rel", array('name'=>trim($cycleinfo['release']), 'rel_category_id'=>1, "owner_id"=>$this->params['owner_id']),array('name'));
				$this->tool->getElementId("os_rel", array('rel_id'=>$rel_id, "os_id"=>$os_id));
				
				if(empty($cycleinfo['creater']))
					continue;
				
				$cycleinfo['creater'] = explode("+", $cycleinfo['creater']);
				$res = $this->params['useradmin']->query("select id from users where username = '".trim($cycleinfo['creater'][0])."'");
				if($row = $res->fetch())
						$creater_id = $row['id'];
				$cycleinfo['tester'] = explode(",", $cycleinfo['tester']);
				foreach($cycleinfo['tester'] as $tester){
						$tester= explode("+", trim($tester));
						$res = $this->params['useradmin']->query("select id from users where username = '".trim($tester[0])."'");
						if($row = $res->fetch())
								$testers[] = $row['id'];
				}
				if(empty($testers))
						$testers[] = $creater_id;
				$tester_ids = implode(",", $testers);
				$cycleinfo['platform'] = strtolower($cycleinfo['platform']);
				$cycle_type_id = $this->tool->getElementId("cycle_type", array('name'=>$cycleinfo['cycletype']),array('name'));
				// if(!empty($cycleinfo['update'])){
					// $this->params[$cycleinfo['platform']]['cycle_id'] = $cycle_id = $this->tool->getExistedId("cycle", array('name'=>$cycleinfo['update']), array('name'));		
				// }
				// else{
					// if(empty($cycleinfo['myname']))
						// continue;
					// $name = $name."-".$cycleinfo['cycletype']."-".$cycleinfo['release']."-".$cycleinfo['myname'];
					// $data = array('name'=>$name, 'prj_ids'=>$prj_id, 'rel_id'=>$rel_id, 'creater_id'=>$creater_id, 'tester_ids'=>$tester_ids,  
						// 'group_id'=>$this->params['group_id'], 'testcase_type_ids'=>$this->params['testcase_type_ids'], 
						// 'cycle_type_id'=>$cycle_type_id, "start_date"=>$date);	
					// $this->params[$cycleinfo['platform']]['cycle_id'] = $cycle_id = $this->tool->getElementId("cycle", $data, array('name'));
					// $this->tool->getElementId("build_target_cycle", array('cycle_id'=>$cycle_id, 'build_target_id'=>1));
					// $this->tool->getElementId("compiler_cycle", array('cycle_id'=>$cycle_id, 'compiler_id'=>1));
					// $this->tool->getElementId("cycle_prj", array('cycle_id'=>$cycle_id, 'prj_id'=>$prj_id));
					// $this->tool->getElementId("cycle_testcase_type", array('cycle_id'=>$cycle_id, 'testcase_type_id'=>$this->params['testcase_type_ids']));
					// foreach($testers as $tester_id)
						// $this->tool->getElementId("cycle_tester", array('cycle_id'=>$cycle_id, 'tester_id'=>$tester_id));
				// }
				$this->params[$cycleinfo['platform']]['prj_id'] = $prj_id;
				$this->params[$cycleinfo['platform']]['os'] = $os;
				
			}
		}
	}
	
	protected function process_cycle_plan($title, $sheet_data){
		print_r("process plan");
		$ret = "";
		$key_fields = array('cycle_id', 'testcase_id', 'test_env_id', 'prj_id', 'compiler_id', 'build_target_id', 'codec_stream_id');
		foreach($sheet_data as $case){
			$testcase_module_id = $this->tool->getExistedId("testcase_module", array('name'=>$case['modules']), array("name"));
			if('error' == $testcase_module_id){
				$ret .= "Module name {$case['modules']} is incorrect"."<br>\n";
				continue;
			}
			foreach($case as $key=>$val){
				$key = strtolower($key);
				if('modules' == $key || 'owner' == $key)
					continue;
				if(empty($this->params[$key]['prj_id']))
					continue;
				$prj_id = $this->params[$key]['prj_id'];
				$val = explode("+", $val);
				foreach($val as $v){
					$new_sql = '';
					switch(strtolower(trim($v))){
						case 'function':
						case 'func':
						case 'fun':
							$this->params['modules'][$prj_id][] = $testcase_module_id;
							break;
					}
				}
			}
		}
		return $ret;
	}
	
	protected function process_function($title, $sheet_data){
		print_r("process function");
		$ret = "";
		$caseinfo = array();
		$key_fields = array('cycle_id', 'testcase_id', 'test_env_id', 'prj_id', 'compiler_id', 'build_target_id', 'codec_stream_id');
		foreach($sheet_data as $case){
			if(empty($case['name'])){
				$ret .= "Case name is empty"."<br>\n";
				continue;
			}
			if(empty($case['module'])){
				$ret .= "Module name is empty"."<br>\n";
				continue;
			}
			$testcase_module_id = $this->tool->getExistedId("testcase_module", array('name'=>$case['module']), array("name"));
			if('error' == $testcase_module_id){
				$ret .= "Module name {$case['module']} is incorrect"."<br>\n";
				continue;
			}
			// $committer = explode("+", $case['committer']);
			// $res = $this->params['useradmin']->query("select * from users where username='".trim($committer[0])."'");
			// if($info = $res->fetch())
				// $committer_id = $info['id'];
			// else
				// $committer_id = 0;
			foreach($case as $key=>$val){
				if(strtolower($val) != "y")
					continue;
				$key = strtolower($key);
				print_r("~~~/");
				if(empty($this->params[$key]['prj_id']))
					continue;
				print_r("~~~/");
				$prj_id = $this->params[$key]['prj_id'];
				if(!isset($this->params['modules'][$prj_id]))
					continue;
				if(!in_array($testcase_module_id, $this->params['modules'][$prj_id]))
					continue;
				if(isset($caseinfo[$case['name']]))
					$testcase_id = $caseinfo[$case['name']];
				else
					$testcase_id = $caseinfo[$case['name']] = $this->tool->getExistedId("testcase", array('code'=>$case['name']), array("code"));
				if('error' == $testcase_module_id)
					break;	
print_r("yyyyyyyyyyyyyyyyyyyyyy");
				$this->tool->getElementId('function_case_for_spring', array("testcase_id"=>$testcase_id, "prj_id"=>$prj_id));
				$res = $this->tool->query("select ver.testcase_priority_id, ptv.testcase_ver_id, ptv.prj_id from prj_testcase_ver ptv".
					" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
					" left join testcase on testcase.id = ver.testcase_id".
					" where ptv.prj_id = $prj_id and ptv.testcase_id = $testcase_id". 
					" and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.
					") and testcase.isactive = ".ISACTIVE_ACTIVE.
					" and ver.testcase_priority_id in (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")");
				if($info = $res->fetch()){
					if(in_array($info['testcase_priority_id'], array(TESTCASE_PRIORITY_P1, TESTCASE_PRIORITY_P2, TESTCASE_PRIORITY_P3))){
						// $cycle_detail = array('cycle_id'=>$this->params[$key]['cycle_id'], 'testcase_id'=>$testcase_id, 'testcase_ver_id'=>$info['testcase_ver_id'], 
							// 'codec_stream_id'=>0, 'test_env_id'=>1, 'prj_id'=>$info['prj_id'], 'build_target_id'=>1, 'compiler_id'=>1);//, 'tester_id'=>$committer_id);
						$this->tool->getElementId('function_case_for_spring', array("testcase_id"=>$testcase_id, "prj_id"=>$info['prj_id']));
					}
				}
			}
		}
		return $ret;
	}
	
	protected function analyze_testcase($sheet, $title){
		$this->analyze_linux($sheet, $title);
	}
	
	protected function analyze_test_plan($sheet, $title){
		$this->analyze_linux($sheet, $title);
	}
	
	protected function analyze_cycle_info($sheet, $title){
		$highestRow = $sheet->getHighestRow(); // e.g. 10
		$highestColumn = $sheet->getHighestColumn(); // e.g 'F'
 // print_r("title = $title, hiColumn = $highestColumn, highestRow = $highestRow\n");		
		$cm = $this->getColumnMap($title, $highestColumn);
		if (!empty($cm)){
			for($col = $cm['start_col']; $col <= $highestColumn; $col ++){
				foreach($cm['rows'] as $key=>$row){
					$this->parse_result[$title][$col][$key] = trim($this->getCell($sheet, $row, $col));
				}
			}
		}
	}
	
	private function analyze_linux($sheet, $title){
		$highestRow = $sheet->getHighestRow(); // e.g. 10
		$highestColumn = $sheet->getHighestColumn(); // e.g 'F'
 // print_r("title = $title, hiColumn = $highestColumn, highestRow = $highestRow\n");		
		$cm = $this->getColumnMap($title, $highestColumn);
		if (!empty($cm)){
			$cm['start_row'] = 1;
			for($row = $cm['start_row']; $row <= $highestRow; $row ++){
				if(1 == $row){
					foreach($cm['columns'] as $key=>$col){
						$key = strtolower(trim($this->getCell($sheet, $row, $col)));
						$columns[$key] = $col;
					}
				}
				else{
					if(empty($columns))
						continue;
					foreach($columns as $key=>$col){
						$this->parse_result[$title][$row][$key] = trim($this->getCell($sheet, $row, $col));
						if(!empty($this->parse_result[$title][$row][$key]))
							continue;
						if(empty($cm['merge_columns']))
							continue;
						if(empty($cm['merge_columns'][$key]))
							continue;
						if(empty($this->parse_result[$title][$row-1]))
							continue;
						$this->parse_result[$title][$row][$key] = $this->parse_result[$title][$row-1][$key];
					}
				}
			}
		}
	}
	
}
?>