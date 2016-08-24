<?php
/*
Import From a excel file of Linux, including cycle info, module plan info and case plan info
*/
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_cycle.php');

class xt_zzvw_cycle_importer_generate_linux_cycle extends xt_zzvw_cycle_importer_cycle{
	
	protected function process(){
		print_r(" Start to process......\n<BR />");
		$ret1 = $this->process_cycle_info('cycle info', $this->parse_result['cycle info']);
		$ret2 = $this->process_cycle_plan('Test plan', $this->parse_result['Test plan']);
		if(!empty($ret2)){
			print_r("Error!"."<br>\n");
			print_r("Case Add To Cycle From Sheet Test Plan Error!"."<br>\n");
			print_r($ret2."<br>\n");
			print_r("Please Do Correct Them, Then Upload Aganin<br>\n");
		}
		$ret3 = $this->process_function('Testcase', $this->parse_result['Testcase']);
		if(!empty($ret3)){
			print_r("Error!"."<br>\n");
			print_r("Case Add To Cycle From Sheet TestCase Error!"."<br>\n");
			print_r($ret3."<br>\n");
			print_r("Please Do Correct Them, Then Upload Aganin<br>\n");
		}
		if(!empty($this->params["category_info"]))
			$this->process_category();
		unset($this->parse_result);
		if(empty($ret2) && empty($ret3)){
			print_r("Cycle Create Done!");
			print_r("\n<br />Now you can close the dialog\n");
		}
		if(!empty($this->params['cycle_id']))
			print_r("element:".json_encode($this->params['cycle_id']));
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
				$prj = $name = $chip."-".strtoupper(trim($matches[2]))."-".ucwords($os);
// print_r($name."\n");
				$prj_id = $this->tool->getElementId("prj", array('name'=>$name, 'os_id'=>$os_id, 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id), 
					array('os_id', 'chip_id', 'board_type_id'));
					
				if(empty($cycleinfo['release']))
					continue;
				
				// $rel_id = $this->tool->getElementId("rel", array('name'=>trim($cycleinfo['release']), 'rel_category_id'=>1, "owner_id"=>$this->params['owner_id']),array('name'));
				$rel_id = $this->tool->getElementId("rel", array('name'=>trim($cycleinfo['release']), 'rel_category_id'=>1),array('name'));
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
				$this->params[$cycleinfo['platform']]['category_info'] = array();
				if(!empty($cycleinfo['update'])){
					$this->params[$cycleinfo['platform']]['cycle_id'] = $this->params['cycle_id'][$prj] = $cycle_id = $this->tool->getExistedId("cycle", array('name'=>$cycleinfo['update']), array('name'));		
					if($cycle_id == "error")
						continue;
				}
				else{
					if(empty($cycleinfo['myname']))
						continue;
					$name = $name."-".$cycleinfo['cycletype']."-".$cycleinfo['release']."-".$cycleinfo['myname'];
					$data = array('name'=>$name, 'prj_ids'=>$prj_id, 'rel_id'=>$rel_id, 'creater_id'=>$creater_id, 'tester_ids'=>$tester_ids,  
						'group_id'=>$this->params['group_id'], 'testcase_type_ids'=>$this->params['testcase_type_ids'], 
						'cycle_type_id'=>$cycle_type_id, "start_date"=>$date);	
					$this->params[$cycleinfo['platform']]['cycle_id'] = $this->params['cycle_id'][$prj] = $cycle_id = $this->tool->getElementId("cycle", $data, array('name'));
					$this->tool->getElementId("build_target_cycle", array('cycle_id'=>$cycle_id, 'build_target_id'=>1));
					$this->tool->getElementId("compiler_cycle", array('cycle_id'=>$cycle_id, 'compiler_id'=>1));
					$this->tool->getElementId("cycle_prj", array('cycle_id'=>$cycle_id, 'prj_id'=>$prj_id));
					$this->tool->getElementId("cycle_testcase_type", array('cycle_id'=>$cycle_id, 'testcase_type_id'=>$this->params['testcase_type_ids']));
					foreach($testers as $tester_id)
						$this->tool->getElementId("cycle_tester", array('cycle_id'=>$cycle_id, 'tester_id'=>$tester_id));
					if(!empty($cycleinfo['category_info'])){
						$category_info = array();
						foreach($cycleinfo['category_info'] as $key=>$type){
							$keys = explode("_", $key);
							if(!empty($keys[1])){
								$testcase_category_id = $this->tool->getElementId("testcase_category", array('name'=>ucfirst(trim($keys[1]))));
								if(empty($type) && !empty($keys[2]))
									$type = $keys[2];
								$type = strtolower($type);
								$category_info[$testcase_category_id] =  explode("+", $type);
							}
						}
						$this->params['category_info'][$cycleinfo['platform']] = $category_info;
					}
				}
				$this->params[$cycleinfo['platform']]['prj_id'] = $prj_id;
				$this->params[$cycleinfo['platform']]['os'] = $os;
				if(!empty($cycleinfo['add'])){
					$key_fields = array('cycle_id', 'testcase_id', 'test_env_id', 'prj_id', 'compiler_id', 'build_target_id', 'codec_stream_id');
					$res = $this->tool->query("SELECT * FROM cycle_detail LEFT JOIN cycle ON cycle.id = cycle_detail.cycle_id".
						" WHERE cycle.name='".$cycleinfo['add']."' AND cycle_detail.result_type_id in (".RESULT_TYPE_FAIL.",".RESULT_TYPE_NT.",".RESULT_TYPE_NS.")");
					while($row = $res->fetch()){
						if($row['prj_id'] != $prj_id)
							continue;
						$res_new = $this->tool->query("SELECT ptv.testcase_ver_id as testcase_ver_id FROM prj_testcase_ver ptv".
							" LEFT JOIN testcase_ver ver ON ver.id = ptv.testcase_ver_id LEFT JOIN testcase ON testcase.id = ver.testcase_id".
							" WHERE ptv.id IS NOT NULL AND ptv.prj_id=".$prj_id." AND ptv.testcase_ver_id=".$row['testcase_ver_id'].
							" AND ptv.testcase_id=".$row['testcase_id']." AND testcase.isactive=".ISACTIVE_ACTIVE.
							" AND ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.") LIMIT 1");
						if($row_new = $res_new->fetch()){
							$insert = array("cycle_id"=>$cycle_id, "test_env_id"=>$row["test_env_id"], "codec_stream_id"=>0,
								"prj_id"=>$prj_id, "testcase_id"=>$row['testcase_id'], "testcase_ver_id"=>$row["testcase_ver_id"],
								"compiler_id"=>$row["compiler_id"], "build_target_id"=>$row["build_target_id"]);
							$this->tool->getElementId('cycle_detail', $insert, $key_fields);
						}
					}
				}
			}
		}
	}
	
	protected function process_cycle_plan($title, $sheet_data){
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
				$sql = "select ver.testcase_priority_id, ptv.testcase_ver_id, ptv.prj_id, ptv.testcase_id,".
					" testcase.testcase_category_id as testcase_category_id, ver.auto_level_id as auto_level_id from prj_testcase_ver ptv".
					" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
					" left join testcase on testcase.id = ver.testcase_id".
					" where ptv.prj_id = $prj_id and ptv.testcase_id = ver.testcase_id and ptv.testcase_id = testcase.id".
					" and testcase.testcase_module_id = $testcase_module_id".
					" and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")".
					" and testcase.isactive = ".ISACTIVE_ACTIVE;
				$val = explode("+", $val);
				foreach($val as $v){
					$new_sql = '';
					switch(strtolower(trim($v))){
						case 'ns':
						case 'n/s':
						case 'na':
						case 'n/a':
						case 'nt':
						case 'n/t':
							break;
						case 'bat':
							$new_sql = $sql." and ver.testcase_priority_id = ".TESTCASE_PRIORITY_P1; //p1
							break;
						case 'auto':
							$new_sql = $sql." and ver.auto_level_id in (".AUTO_LEVEL_AUTO.",".AUTO_LEVEL_PARTIAL_TO_AUTO.") and ver.testcase_priority_id in (1,2,3)";//auto + p2a
							break;
						case 'function':
						case 'func':
						case 'fun':
							$this->params['modules'][$prj_id][] = $testcase_module_id;
							break;
						case 'full':
							$new_sql = $sql." and ver.testcase_priority_id in (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")";
							break;
						default:
							if($v != "")
								$ret .= " '{$v}' is illegal for module {$case['modules']} for {$key} in sheet Test Plan"."<br>\n";
							break;
					}
					$categories = array();
					if(!empty($this->params['category_info'][$key]))
						$categories = array_keys($this->params['category_info'][$key]);						
					if(!empty($new_sql)){
						$res = $this->tool->query($new_sql);
						$deadline = date("Y-m-d",strtotime('+3 days'));
						while($info = $res->fetch()){
							if(in_array($info['testcase_priority_id'], array(TESTCASE_PRIORITY_P1, TESTCASE_PRIORITY_P2, TESTCASE_PRIORITY_P3))){	
								// if(in_array($info['testcase_category_id'], $categories) && isset($this->params[$key]['category_info'][$info['testcase_category_id']])){
									// $types = $this->params[$key]['category_info'][$info['testcase_category_id']];
									// if(in_array("bat", $types)){
										// if($info['testcase_priority_id'] != TESTCASE_PRIORITY_P1)
											// continue;
									// }
									// else if(in_array("auto", $types)){
										// if(!in_array($info['auto_level_id'], array(AUTO_LEVEL_AUTO, AUTO_LEVEL_PARTIAL_TO_AUTO)))
											// continue;
									// } 
									// else if(in_array("full", $types)){
										// if(!in_array($info['testcase_priority_id'],  array(TESTCASE_PRIORITY_P1, TESTCASE_PRIORITY_P2, TESTCASE_PRIORITY_P3)))
											// continue;
									// }
									// else
										// continue;
								// }
								if(in_array($info['testcase_category_id'], $categories))continue;
								$cycle_detail = array('cycle_id'=>$this->params[$key]['cycle_id'], 'testcase_id'=>$info['testcase_id'], 'testcase_ver_id'=>$info['testcase_ver_id'], 
									'codec_stream_id'=>0, 'test_env_id'=>$this->params['test_env_id'], 'prj_id'=>$info['prj_id'], 'build_target_id'=>1, 'compiler_id'=>1);//, 'tester_id'=>$owner_id);
								if($info['testcase_priority_id'] == TESTCASE_PRIORITY_P1)
									$cycle_detail['deadline'] = $deadline;
								$cycle_detail_id = $this->tool->getElementId('cycle_detail', $cycle_detail, $key_fields);
							}
						}
					}
				}
			}
		}
		return $ret;
	}
	
	protected function process_function($title, $sheet_data){
		$ret = "";
		$caseinfo = array();
		$deadline = date("Y-m-d",strtotime('+3 days'));
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
				if(empty($this->params[$key]['prj_id']))
					continue;
				$prj_id = $this->params[$key]['prj_id'];
				
				if(isset($caseinfo[$case['name']]))
					$testcase_id = $caseinfo[$case['name']];
				else
					$testcase_id = $caseinfo[$case['name']] = $this->tool->getExistedId("testcase", array('code'=>$case['name']), array("code"));
				if('error' == $testcase_id)
					break;	
				$this->params["function"][] = $testcase_id;
				if(!isset($this->params['modules'][$prj_id]))
					continue;
				if(!in_array($testcase_module_id, $this->params['modules'][$prj_id]))
					continue;
				$categories = array();
				if(!empty($this->params['category_info'][$key]))
					$categories = array_keys($this->params['category_info'][$key]);	
				$res = $this->tool->query("select ver.testcase_priority_id, ptv.testcase_ver_id, ptv.prj_id, testcase.testcase_category_id as testcase_category_id from prj_testcase_ver ptv".
					" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
					" left join testcase on testcase.id = ver.testcase_id".
					" where ptv.prj_id = $prj_id and ptv.testcase_id = $testcase_id". 
					" and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.
					") and testcase.isactive = ".ISACTIVE_ACTIVE.
					" and ver.testcase_priority_id in (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")");
				if($info = $res->fetch()){
					if(in_array($info['testcase_priority_id'], array(TESTCASE_PRIORITY_P1, TESTCASE_PRIORITY_P2, TESTCASE_PRIORITY_P3))){
						// if(in_array($info['testcase_category_id'], $categories) && isset($this->params[$key]['category_info'][$info['testcase_category_id']])){
							// $types = $this->params[$key]['category_info'][$info['testcase_category_id']];
							// if(!in_array('func', $types) && !in_array('fun', $types) && !in_array('function', $types))
								// continue;
						// }
						if(in_array($info['testcase_category_id'], $categories))continue;
						$cycle_detail = array('cycle_id'=>$this->params[$key]['cycle_id'], 'testcase_id'=>$testcase_id, 'testcase_ver_id'=>$info['testcase_ver_id'], 
							'codec_stream_id'=>0, 'test_env_id'=>1, 'prj_id'=>$info['prj_id'], 'build_target_id'=>1, 'compiler_id'=>1);//, 'tester_id'=>$committer_id);
						if($info['testcase_priority_id'] == TESTCASE_PRIORITY_P1)
							$cycle_detail['deadline'] = $deadline;
						$cycle_detail_id = $this->tool->getElementId('cycle_detail', $cycle_detail, $key_fields);
					}
				}
			}
		}
		return $ret;
	}
	
	protected function process_category(){
		$deadline = date("Y-m-d",strtotime('+3 days'));
		$key_fields = array('cycle_id', 'testcase_id', 'test_env_id', 'prj_id', 'compiler_id', 'build_target_id', 'codec_stream_id');
		foreach($this->params['category_info'] as $key=>$categoryInfo){
			$prj_id = $this->params[$key]['prj_id'];
			$sql = "select ver.testcase_priority_id, ptv.testcase_ver_id, ptv.prj_id, ptv.testcase_id,".
				" testcase.testcase_category_id, ver.auto_level_id, testcase.testcase_module_id".
				" from prj_testcase_ver ptv".
				" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
				" left join testcase on testcase.id = ver.testcase_id";
			$where1 = " where testcase.testcase_type_id=".TESTCASE_TYPE_LINUX_BSP." AND ptv.prj_id = $prj_id AND ptv.testcase_id = ver.testcase_id AND ptv.testcase_id = testcase.id";
			$where2 = " AND ver.testcase_priority_id in (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")".
				" AND ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")".
				" AND testcase.isactive = ".ISACTIVE_ACTIVE;
			foreach($categoryInfo as $testcase_category_id=>$info){
				$where3 = " AND testcase.testcase_category_id={$testcase_category_id}";
				foreach($info as $type){
					$type = strtolower($type);
					$where4 = "";
					switch($type){
						case 'ns':
						case 'n/s':
						case 'na':
						case 'n/a':
						case 'nt':
						case 'n/t':
							break;
						case 'bat':
							$where4 = " AND ver.testcase_priority_id = ".TESTCASE_PRIORITY_P1; //p1
							break;
						case 'function':
						case 'func':
						case 'fun':
						case 'full':
							$where4 = " AND 1";
							break;
						case 'auto':
							$where4 = " AND ver.auto_level_id in (".AUTO_LEVEL_AUTO.",".AUTO_LEVEL_PARTIAL_TO_AUTO.") and ver.testcase_priority_id in (1,2,3)";//auto + p2a
							break;
					}
					if(!empty($where4)){
						$tmp = $sql.$where1.$where2.$where3.$where4;
						$res = $this->tool->query($tmp);
						while($info = $res->fetch()){
							if(in_array($type, array("fun", "func", "function"))){
								if(!in_array($info["testcase_id"], $this->params["function"]))continue;
							}
							$cycle_detail = array('cycle_id'=>$this->params[$key]['cycle_id'], 'testcase_id'=>$info['testcase_id'], 'testcase_ver_id'=>$info['testcase_ver_id'], 
								'codec_stream_id'=>0, 'test_env_id'=>$this->params['test_env_id'], 'prj_id'=>$info['prj_id'], 'build_target_id'=>1, 'compiler_id'=>1);//, 'tester_id'=>$owner_id);
							if($info['testcase_priority_id'] == TESTCASE_PRIORITY_P1)
								$cycle_detail['deadline'] = $deadline;
							$cycle_detail_id = $this->tool->getElementId('cycle_detail', $cycle_detail, $key_fields);
						}
					}
				}
			}
		}
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
					if($key == "add"){
						for($i=1;$i<=4;$i++){
							$new_key = trim($this->getCell($sheet, ($row + $i), $cm['category_col']));
							$this->parse_result[$title][$col]["category_info"][$new_key] = trim($this->getCell($sheet, ($row + $i), $col));
						}
					}
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