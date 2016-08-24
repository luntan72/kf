<?php
require_once('table_desc.php');

class xt_zzvw_cycle extends table_desc{
	protected function init($params){
        parent::init($params);
		$db = $this->params['db'];
		$table = $this->params['table'];
		$week = $this->generateWeekList();
		$current_week = date("W");
		$cart_data = new stdClass;
		$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"status_id","op":"eq","data":1}]}';
		$this->options['linktype'] = 'infoLink';
		$this->options['real_table'] = 'cycle';
        $this->options['list'] = array(
			'id'=>array('label'=>'ID', 'hidden'=>true),
			'name'=>array('width'=>290),
			'os_id'=>array('label'=>'OS', 'hidden'=>true),
			'board_type_id'=>array('label'=>'Board','hidden'=>true),
			'chip_id'=>array('hidden'=>true),
			'prj_ids'=>array('width'=>160, 'label'=>'Projects', 'hidden'=>true), //'formatter'=>'seeMoreLink'),
			'rel_id'=>array('label'=>'Release', 'editrules'=>array('required'=>true)),
			'compiler_ids'=>array('label'=>'IDES', 'width'=>100, 'hidden'=>true),
			'cycle_type_id'=>array('label'=>'Cycle Type', 'hidden'=>true),
			'build_target_ids'=>array('label'=>'Targets', 'hidden'=>true),
			'testcase_type_ids'=>array('label'=>'Case Types', 'hidden'=>true),
			'group_id'=>array('label'=>'Group', 'formatoptions'=>array('value'=>$this->userAdmin->getGroups(true)), 
			'formatter'=>'select', 'searchoptions'=>array('value'=>$this->userAdmin->getGroups(true)), 'stype'=>'select'),
			'cycle_category_id'=>array('label'=>'Category', 'hidden'=>true),
			'cycle_status_id'=>array('label'=>'Status','hidden'=>true),
			'start_date'=>array('label'=>'Start Date','hidden'=>true),
			'end_date'=>array('label'=>'End Date','hidden'=>true),
			'tester_ids'=>array('label'=>'Tester', 'hidden'=>true, 'cart_db'=>'useradmin', 'cart_table'=>'users', 'cart_data'=>json_encode($cart_data)),
			'assistant_owner_id'=>array('label'=>'Assistant', 'hidden'=>true, 'editrules'=>array('required'=>false)),
			'test_env_id'=>array('label'=>'Env', 'hidden'=>true),
			'creater_id'=>array('label'=>'Creater', 'width'=>80),
			'description'=>array('width'=>200),
			'finished_cases'=>array('label'=>'Finished', 'excluded'=>true, 'width'=>50, 'search'=>false),
			'pass_cases'=>array('label'=>'Passed', 'excluded'=>true, 'width'=>85, 'search'=>false),
			'fail_cases'=>array('label'=>'Failed', 'excluded'=>true, 'width'=>40, 'search'=>false),
			'total_cases'=>array('label'=>'Total', 'excluded'=>true, 'width'=>50, 'search'=>false),
			'week'=>array('label'=>'Week', 'editrules'=>array('required'=>true), 'edittype'=>'select', 'type'=>'select', 'excluded'=>true, 'hidden'=>true, 'hidedlg'=>true, 'defval'=>$current_week, 'editoptions'=>array('value'=>$week)),
			'myname'=>array('label'=>'MyName', 'editrules'=>array('required'=>true), 'edittype'=>'text',  'type'=>'text', 'excluded'=>true, 'hidden'=>true, 'hidedlg'=>true),
			'zzvw_mcuauto_request_ids'=>array('editrules'=>array('required'=>false), "data_source_db"=>"mydb", "data_source_table"=>"zzvw_mcuauto_request", 'hidden'=>true),
			'*'=>array('hidden'=>true)
		);
		$this->getQueryInfo($db, $table);
		$this->getEditInfo($db, $table);

		$this->options['ver'] = '1.0';
		$this->options['gridOptions']['label'] = 'Cycle';
		$this->options['gridOptions']['inlineEdit'] = false;
		$this->options['gridOptions']['search'] = false;
		$this->options['navOptions']['refresh'] = false;
//		$this->options['tags'] = true;
		$this->options['subGrid'] = array('expandField'=>'cycle_id', 'db'=>'xt', 'table'=>'zzvw_cycle_detail');
		$this->options['linkTables'] = array('m2m'=>array('prj', 'build_target', 'compiler', 'tester', 'testcase_type', 'os'=>array('link_table'=>'prj', 'self_link_field'=>'id'), 
			'chip'=>array('link_table'=>'prj', 'self_link_field'=>'id'), 'board_type'=>array('link_table'=>'prj', 'self_link_field'=>'id')));
	}
	
	private function getQueryInfo($db, $table){
		// default
		$this->options['query'] = array(
			'buttons'=>array(
				//'new'=>array('label'=>'New', 'onclick'=>'XT.go("/jqgrid/jqgrid/oper/information/db/xt/table/zzvw_cycle/element/0")', 'title'=>'Create New Cycle'),
				// 'query_new'=>array('label'=>'New', 'onclick'=>'XT.grid_query_add("mainContent", "'.$db.'", "'.$table.'")', 'title'=>'Create New Cycle'),
				// 'query_import'=>array('label'=>'Upload', 'onclick'=>'xt.zzvw_cycle.import()', 'title'=>'Import Cycle'),
				'query_new'=>array('label'=>'New', 'title'=>'Create New Cycle'),
				'query_import'=>array('label'=>'Upload', 'title'=>'Import Cycle'),
			), 
			'normal'=>array('name'=>array('label'=>'Name'), 'os_id', 'chip_id', 'board_type_id', 
				'prj_id'=>array('label'=>'Project', 'type'=>'single_multi', 'init_type'=>'single', 
					'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'Project'), 'excluded'=>true), 'rel_id', 
				'testcase_type_id'=>array('label'=>'Case Type', 'type'=>'single_multi', 'init_type'=>'single', 
					'single_multi'=>array('db'=>$db, 'table'=>'testcase_type', 'label'=>'Testcase Type'), 'excluded'=>true), 
				'cycle_status_id', 'creater_id', 'created'=>array('type'=>'date', 'DATA_TYPE'=>'datetime')), 
			'advanced'=>array('compiler_ids', 'cycle_type_id', 'build_target_ids', 'cycle_category_id', 'tester_ids')
		);
		
		// update from other cycle ( for linux )
		if(isset($this->params['container'])){
			if($this->params['container'] == 'div_update_from_other_cycle'){
				$this->options['query']['normal'] = array('name'=>array('label'=>'Name'), 'prj_id', 'testcase_type_id', 'creater_id', 'rel_id',
					'cycle_type_id', 'cycle_category_id', 'created'=>array('type'=>'date', 'DATA_TYPE'=>'datetime'), 'cycle_status_id', 'tester_ids');
				unset($this->options['query']['advanced']);
			}
		}
	}
	
	private function getEditInfo($db, $table){
		$current_week = date("W");
		$this->options['edit'] = array('group_id', 
			'create_type','os_id'=>array('label'=>'Os', 'title'=>'If you want to selec Multi os, pls click + button to transfer to multiselect', 'editable'=>true, 'type'=>'single_multi', 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'os', 'label'=>'Os')), 
			'zzvw_mcuauto_request_ids'=>array('label'=>'Dp Requests', 'type'=>'cart', "data_source_table"=>"zzvw_mcuauto_request", "data_source_db"=>"mydb", 'cart_table'=>'zzvw_mcuauto_request', 'cart_db'=>'mydb'),
			'chip_id'=>array('editable'=>true), 'board_type_id'=>array('editable'=>true), 
			'prj_ids'=>array('label'=>'Projects', 'title'=>'If you want to selec Multi projects, pls click + button to transfer to multiselect', 'type'=>'single_multi', 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'ProjectS')), 
			'testcase_type_ids'=>array('label'=>'Case Types', 'If you want to selec Multi testcase type, pls click + button to transfer to multiselect', 'editable'=>true, 'type'=>'single_multi', 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'testcase_type', 'label'=>'Case Types')),
			'compiler_ids'=>array('label'=>'IDEs', 'If you want to selec Multi compilers, pls click + button to transfer to multiselect', 'type'=>'single_multi', 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'compiler', 'label'=>'IDES')), 
			'build_target_ids'=>array('label'=>'Targets', 'If you want to selec Multi targets, pls click + button to transfer to multiselect', 'type'=>'single_multi', 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'build_target', 'label'=>'Targets')),
			'rel_id', 'cycle_type_id', 'test_env_id', 'week'=>array('editable'=>true, 'defval'=>$current_week),
			'myname'=>array('editable'=>true), 'name', 'description', 'start_date'=>array('editrules'=>array('required'=>true)), 'end_date', 'tester_ids'=>array('label'=>'Testers', 'type'=>'cart', 'cart_db'=>'useradmin', 'cart_table'=>'users'), 
			 'assistant_owner_id', 'creater_id','tag'=>array('excluded=>true'), 'template'=>array('excluded'=>true), 
			 'new_zzvw_mcuauto_request_ids'=>array('excluded'=>true)
			//'config_file'=>array('label'=>'YML Type', 'editable'=>true, 'type'=>'select'), 'uploaded_file'=>array('label'=>'YML Zip', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'file')
			// 'zzvw_mcuauto_request_ids'=>array('label'=>'DaPeng ReqId', 'type'=>'link', 'link'=>array("prev"=>"http://dapeng/dapeng/EditMcuautoRequest/", "next"=>"/")),
		);
	}
	
	public function calcSqlComponents($params, $limited = true){
		$components = parent::calcSqlComponents($params, $limited);
		if(empty($components['order']))
			$components['order'] = 'id desc';
		return $components;
	}
	
	public function getRowRole($table_name = '', $id = 0){
		$role = parent::getRowRole($table_name, $id);
		if(empty($this->params['id'])){
			if(in_array('tester', $this->userInfo->roles))
				$role = array('cycle_newer');
		}
// print_r($role);		
		return $role;
	}

	public function getButtons(){
		$buttons = array(
			'freeze'=>array('caption'=>'Freeze', 'title'=>'Freeze the selected cycles'),
		);
		$btns = parent::getButtons();
		unset($btns['activate']);
		unset($btns['inactivate']);
		return array_merge($btns, $buttons);
	}
	
	public function getMoreInfoForRow($row){
		$row = parent::getMoreInfoForRow($row);
		$blank = 0;
		$caseLists = array();
		$counts = array('total_cases'=>0, 'pass_cases'=>0, 'fail_cases'=>0, 'finished_cases'=>0);
		$row = array_merge($row, $counts);
		
		$res = $this->tool->query("SELECT codec_stream_id, test_env_id, prj_id, result_type_id, COUNT(*) AS cases FROM cycle_detail".
			" WHERE cycle_id = {$row['id']} GROUP BY codec_stream_id, test_env_id, prj_id, result_type_id ORDER BY codec_stream_id");
		while($info = $res->fetch()){
			$caseLists[$info['test_env_id']][$info['prj_id']][$info['codec_stream_id']][$info['result_type_id']] = $info['cases'];
		}
		
		foreach($caseLists as $envID=>$prjInfo){
			foreach($prjInfo as $prjID=>$streamInfo){
				foreach($streamInfo as $streamID=>$data){
					if(WITHOUT_STREAM == $streamID){
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
							}
							$row['total_cases'] += $count;
						}
					}
					else{
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
		$row['pass_cases'] = sprintf("<span style='color:$color'>%-4d[%5.2f%%]</span>", $row['pass_cases'], $passrate);
		$row['finished_cases'] = sprintf("<span style='color:$color'>%-4d[%5.2f%%]</span>", $row['finished_cases'], $finishrate);
		if(stripos($row['prj_ids'], ",") !== false){
			$row['os_id'] = 0;
			$row['chip_id'] = 0;	
			$row['board_type_id'] = 0;	
		}
		return $row;
	}
	
	protected function getEditFields($params = array()){
		parent::getEditFields($params);
		if (!empty($this->options['edit']['group_id'])){
			$this->options['edit']['group_id']['edittype'] = 'select';
			$this->options['edit']['group_id']['editoptions']['value'] = $this->userAdmin->getUserGroups(array('users_id'=>$this->userInfo->id));		
		}
		if(!empty($this->options['edit']['testcase_type_ids']['single_multi'])){
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$this->testcase_type_ids.'"}]}';
			$this->options['edit']['testcase_type_ids']['single_multi']['data'] = json_encode($cart_data);
		}	
		if(!empty($this->options['edit']['prj_ids']['single_multi'])){
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$this->prj_ids.'"}]}';
			$this->options['edit']['prj_ids']['single_multi']['data'] = json_encode($cart_data);
		}	
		if(!empty($this->options['edit']['os_id']['single_multi'])){
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$this->os_ids.'"}]}';
			$this->options['edit']['os_id']['single_multi']['data'] = json_encode($cart_data);
		}
		
		if (!empty($this->options['edit']['rel_id'])){
			$res = $this->tool->query("select distinct rel.id, rel.name from rel left join os_rel on rel.id = os_rel.rel_id
				where os_rel.os_id in ({$this->os_ids}) order by rel.id desc");
			$rel[0] = '';
			while($row = $res->fetch()){
				$rel[$row['id']] = $row['name'];
			}
			$this->options['edit']['rel_id']['editoptions']['value'] = 
				$this->options['edit']['rel_id']['addoptions']['value'] = $rel;
		}
		if (!empty($this->options['edit']['tester_ids'])){
			$userList= $this->userAdmin->getUserList(array('blank'=>true, 'groups_id'=>$this->userInfo->group_ids));
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.implode(",", array_keys($userList)).'"}]}';
			$this->options['edit']['tester_ids']['cart_data'] = json_encode($cart_data);
		}
		
		if (!empty($this->options['edit']['create_type'])){
			$this->options['edit']['create_type']['edittype'] = 'select';
			$this->options['edit']['create_type']['label'] = 'Create Type';
			$this->options['edit']['create_type']['editoptions']['value'] = array('0'=>'', '1'=>'Mannual', '2'=>'DaPeng');	
		}
		if (!empty($this->options['edit']['tag'])){
			$this->options['edit']['tag']['edittype'] = 'select';
			$res = $this->tool->query("select id, name, creater_id from tag where db_table = 'xt.codec_stream' order by name");
			$tag[0] = '';
			while($info = $res->fetch()){
				$userList = $this->userAdmin->getUserList(array('id'=>$info['creater_id']));
				$tag[$info['id']] = $info['name']."(-- by ".$userList[$info['creater_id']].")";
			}
			$this->options['edit']['tag']['editoptions']['value'] = $tag;	
		}
		if (!empty($this->options['edit']['template'])){
// print_r('group_id'."\n");
			$this->options['edit']['template']['edittype'] = 'select';
			$this->options['edit']['template']['editoptions']['value'] = array('0'=>'', '1'=>'BAT', '2'=>'FUNCTION', '3'=>'FULL');	
		}
				
		if(!empty($this->params['id']) && !is_array($this->params['id'])){
			$this->options['edit']['prj_ids']['init_type'] = 'cart';
			$this->options['edit']['compiler_ids']['init_type'] = 'cart';
			$this->options['edit']['build_target_ids']['init_type'] = 'cart';
			$this->options['edit']['testcase_type_ids']['init_type'] = 'cart';
			
			$sql = "select group_id";
			if (!empty($this->options['edit']['assistant_owner_id']))
				$sql .= ", tester_ids, assistant_owner_id";
			$sql .= " from cycle where id=".$this->params['id'];
			$res = $this->tool->query($sql);
			if($row = $res->fetch()){
				if(!empty($row['tester_ids'])){
					$userlist = $this->userAdmin->getUserList(array('id'=>$row['tester_ids']));
					$userlist[0] = '';
					$this->options['edit']['assistant_owner_id']['editoptions']['value'] = $userlist;	
				}
				$groups = array(GROUP_KSDK, GROUP_USB, GROUP_MQX, GROUP_KIBBLE);
				if(!in_array($row['group_id'], $groups)){
					if(GROUP_FAS != $row['group_id']){
						$this->options['edit']['prj_ids']['init_type'] = 'single';
					}
					$this->options['edit']['compiler_ids']['init_type'] = 'single';
					$this->options['edit']['build_target_ids']['init_type'] = 'single';
					$this->options['edit']['testcase_type_ids']['init_type'] = 'single';
				}
			}
		}
		return $this->options['edit'];
	}
	
	private function _getQuerFields($field, $where, $params){
		if(!empty($this->options['query'][$where][$field])){
			foreach($params as $key=>$value){
				switch($key){
					case "edittype":
						$this->options['query'][$where][$field][$key] = $value;
						break;
					case "cols":
						$this->options['query'][$where][$field][$key] = $value;
						break;
					case "searchoptions":
						foreach($value as $k=>$v){
							if(is_string($v)){
								$this->options['query'][$where][$field][$key][$k] .= $v;
							}
							elseif(is_array($v)){
								$this->options['query'][$where][$field][$key][$k] = $v;
							}
						}
						break;
					case "queryoptions":
						foreach($value as $k=>$v){
							$this->options['query'][$where][$field][$key][$k] = $v;
						}
						break;
					default:
						break;
				}
			}
		}
	}
	
	protected function getQueryFields($params = array()){	
		parent::getQueryFields($params);
		$this->_getQuerFields("cycle_status_id", "advanced", array("queryoptions"=>array("value"=>CYCLE_STATUS_ONGOING)));
		$this->_getQuerFields("cycle_status_id", "normal", array("queryoptions"=>array("value"=>CYCLE_STATUS_ONGOING)));
		$this->_getQuerFields("creater_id", "normal", array("queryoptions"=>array("value"=>$this->userInfo->id)));
		$this->_getQuerFields("tester_ids", "advanced", array("edittype"=>"select"));	
		
		if (!empty($this->options['query']['normal']['testcase_type_id'])){
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$this->testcase_type_ids.'"}]}';
			if(!empty($this->options['query']['normal']['testcase_type_id']['single_multi']))
				$this->options['query']['normal']['testcase_type_id']['single_multi']['data'] = json_encode($cart_data);
		}
		if (!empty($this->options['query']['normal']['prj_id'])){
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$this->prj_ids.'"}]}';
			if(!empty($this->options['query']['normal']['prj_id']['single_multi']))
				$this->options['query']['normal']['prj_id']['single_multi']['data'] = json_encode($cart_data);
		}		
		return $this->options['query'];
	}
	
	private function generateWeekList($preWeek = 8, $postWeek = 10){
		$currentYear = (int)date('y');
		$currentWorkWeek = (int)date('W');
		$refData = array();
		for($i = $currentWorkWeek - $preWeek; $i < $currentWorkWeek + $postWeek; $i ++){
			$j = $i;
			$year = $currentYear;
			if ($i > 52){
				$j = $i - 52;
				$year = $currentYear + 1;
			}
			else if ($i <= 0){
				$j = $i + 52;
				$year = $currentYear - 1;
			}
			if ($j < 10){
				$i = '0'.$i;
				$refData[$i] = $year.'WK0'.$j;
				$i = (int)$i;
			}
			else
				$refData[$i] = $year.'WK'.$j;
		}
		return $refData;
	}
	
	protected function handleFillOptionCondition(){
		// get testcase_type by the group of current user
		$res = $this->tool->query("SELECT GROUP_CONCAT(distinct testcase_type_id) as testcase_type_ids FROM group_testcase_type WHERE group_id in ({$this->userInfo->group_ids})");
		$row = $res->fetch();
		$this->testcase_type_ids = $row['testcase_type_ids'];
		$this->fillOptionConditions['testcase_type_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$row['testcase_type_ids']));
		$this->fillOptionConditions['testcase_type_ids'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$row['testcase_type_ids']));
		// get os by testcase_type above
		$os_ids = array();
		if(!empty($this->testcase_type_ids)){
			$res = $this->tool->query("SELECT DISTINCT os_id from os_testcase_type WHERE testcase_type_id in ({$this->testcase_type_ids})");
			while($row = $res->fetch())
				$os_ids[] = $row['os_id'];
		}
		$this->os_ids = implode(',', $os_ids);
		$this->fillOptionConditions['os_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$os_ids));
		// get project, chip and board_type by os above
		$chip_ids = array();
		$board_type_ids = array();
		$prj_ids = array();
		if(!empty($os_ids)){
			$res = $this->tool->query("SELECT * FROM prj where os_id in (".implode(',', $os_ids).")");
			while($row = $res->fetch()){
				$chip_ids[] = $row['chip_id'];
				$board_type_ids[] = $row['board_type_id'];
				$prj_ids[] = $row['id'];
			}
			$chip_ids = array_unique($chip_ids);
			$board_type_ids = array_unique($board_type_ids);
			$prj_ids = array_unique($prj_ids);
		}
		$this->chip_ids = implode(',', $chip_ids);
		$this->board_type_ids = implode(',', $board_type_ids);
		$this->prj_ids = implode(',', $prj_ids);
		$this->fillOptionConditions['chip_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$chip_ids));
		$this->fillOptionConditions['prj_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$prj_ids));
		$this->fillOptionConditions['prj_ids'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$prj_ids));
		$this->fillOptionConditions['board_type_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$board_type_ids));
		if(!empty($this->os_ids)){
			$res = $this->tool->query("select distinct rel_id from os_rel where os_id in ({$this->os_ids}) order by rel_id desc");
			$rel = array();
			while($row = $res->fetch())
				$rel[] = $row['rel_id'];
			$this->fillOptionConditions['rel_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>implode(",", $rel)));
		}
		$userList= $this->userAdmin->getUserList(array('blank'=>true, 'groups_id'=>$this->userInfo->group_ids));
		$this->fillOptionConditions['tester_ids'] = array(array('field'=>'id', 'op'=>'in', 'value'=>implode(",", array_keys($userList))));
		$this->fillOptionConditions['creater_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>implode(",", array_keys($userList))));
	}
	
	protected function getSpecialFilters(){
		$new_special = array('os_id', 'chip_id', 'board_type_id');
		$special = parent::getSpecialFilters();
		return array_merge($special, $new_special);
	}
	
	protected function specialSql($special, &$ret){
		$new_special = array('os_id', 'chip_id', 'board_type_id');
		if(!empty($special) && !empty($this->options['linkTables'])){		
			$isJoin = false;
			$table = $this->get('table');
			$real_table = $this->get('real_table');
			
			foreach($this->options['linkTables'] as $linkTable=>$linkInfo){
				foreach($special as $k=>$c){
					if(!in_array($c['field'], array($linkTable.'_id', $linkTable.'_ids')))
						continue;
					if(in_array($c['field'], $new_special) && !in_array("prj_id", $special)){
						if(!$isJoin && !empty($linkInfo['link_table']) && $linkInfo['link_table'] == "prj"){
// print_r("abc");
							$isJoin = true;
							$extraLinkTable = "cycle_".$linkInfo['link_table'];
							$extraSelfLinkField1 = $linkInfo['link_table']."_id";
							$extraSelfLinkField2 = $real_table."_id";
							$ret['main']['from'] .= " LEFT JOIN {$extraLinkTable} ON {$table}.id={$extraLinkTable}.{$extraSelfLinkField2}";
							$ret['main']['from'] .= " LEFT JOIN {$linkInfo['link_table']} ON {$extraLinkTable}.{$extraSelfLinkField1}={$linkInfo['link_table']}.{$linkInfo['self_link_field']}";						
						}
					}
					else{
// print_r($linkInfo['link_table']."\n");
						if(!empty($linkInfo['link_table'])){
							if($linkInfo['link_table'] == "cycle_prj"){
								if(!$isJoin){
									$isJoin = true;
									$ret['main']['from'] .= " LEFT JOIN {$linkInfo['link_table']} ON {$table}.id={$linkInfo['link_table']}.{$linkInfo['self_link_field']}";		
								}
							}
							else{
// print_r($linkInfo['link_table']."\n");
								$ret['main']['from'] .= " LEFT JOIN {$linkInfo['link_table']} ON {$table}.id={$linkInfo['link_table']}.{$linkInfo['self_link_field']}";									}
						}
					}
// print_r($ret);
					$v = $c['value'];
					if(is_array($v))
						$v = implode(',', $v);
					$ret['group'] = $this->get('table').'.id';
					$ret['where'] .= " AND {$linkInfo['link_table']}.{$linkInfo['link_field']} IN ($v)";
// print_r($ret);
					unset($special[$k]);
				}
			}
		}
	}
}
?>