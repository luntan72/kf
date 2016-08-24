<?php
require_once('table_desc.php');

class xt_zzvw_cycle_detail extends table_desc{
	protected function init($params){
        parent::init($params);
		$this->options['linktype'] = 'infoLink';	
		$this->options['real_table'] = 'cycle_detail';
        $this->options['list'] = array(
			'id'=>array('formatter'=>'infoLink'),
			'c_f'=>array('hidden'=>true, 'excluded'=>true, 'edittype'=>'text', 'hidedlg'=>true),
			'cycle_id'=>array('hidden'=>true),
			'prj_id'=>array('label'=>'Prj', 'hidden'=>true),
			'chip_id'=>array('hidden'=>true),
			'board_type_id'=>array('hidden'=>true),
			'os_id'=>array('hidden'=>true),
			'd_code'=>array('label'=>'Testcase', 'editable'=>false, 'unique'=>false, 'formatter'=>'text_link', 'formatoptions'=>array('db'=>'xt', 'table'=>'testcase', 'newpage'=>true, 'data_field'=>'testcase_id', 'addParams'=>array('ver'=>'testcase_ver_id'))),
			'summary'=>array('label'=>'Summary', 'width'=>65, 'editable'=>false),
			'compiler_id'=>array('label'=>'IDE', 'hidden'=>true),
			'build_target_id'=>array('label'=>'Target', 'hidden'=>true),
			'test_env_id'=>array('width'=>50, 'hidden'=>true),
			'result_type_id'=>array('label'=>'Result', 'width'=>30, 'editrules'=>array('required'=>true)), 
			'build_result_id'=>array('label'=>'B-Res', 'width'=>30, 'hidden'=>true, 'data_source_table'=>'result_type'),
			'comment'=>array('label'=>'Comment', 'width'=>100),
			'issue_comment'=>array('label'=>'Issue Comment', 'hidden'=>true, 'editable'=>false),
			'defect_ids'=>array('label'=>'CRID/JIRA Key', 'formatter'=>'jira_link', 'required'=>false, 'width'=>50),
			'testcase_type_id'=>array('label'=>'Case Type', 'hidden'=>true, 'editable'=>false),
			'testcase_module_id'=>array('label'=>'Module', 'editable'=>false, 'width'=>30),
			'testcase_category_id'=>array('label'=>'category', 'hidden'=>true, 'editable'=>false),
			'testcase_testpoint_id'=>array('label'=>'Testpoint', 'hidden'=>true),
			'testcase_priority_id'=>array('label'=>'Priority', 'cols'=>6, 'editable'=>false, 'width'=>30),
			'auto_level_id'=>array('width'=>50, 'editable'=>false, 'hidden'=>true),
			'precondition'=>array('label'=>'Precondition', 'hidden'=>true, 'rows'=>8, 'editable'=>false),
			'objective'=>array('hidden'=>true),
			'steps'=>array('label'=>'Steps', 'hidden'=>true, 'rows'=>8, 'editable'=>false),
			'command'=>array('label'=>'CMDline', 'hidden'=>true, 'editable'=>false),
			'expected_result'=>array('label'=>'Expected Result', 'hidden'=>true, 'editable'=>false),
			'resource_link'=>array('label'=>'Resource Link', 'hidden'=>true, 'editable'=>false),
			'deadline'=>array('required'=>false),
			'finish_time'=>array('label'=>'Finished', 'width'=>70),
			'updater_id'=>array('label'=>'Updater', 'width'=>35, 'hidden'=>true),
			'owner_id'=>array('label'=>'Owner', 'width'=>35, 'hidden'=>true),
			'tester_id'=>array('label'=>'Testor', 'width'=>35),
			'isTester'=>array('excluded'=>true, 'hidden'=>true, 'hidedlg'=>true),
			'logs'=>array('hidden'=>true, 'formatter'=>'log_link'),
			'creater_id'=>array('hidden'=>true, 'hidedlg'=>true, 'required'=>false),
			'assistant_owner_id'=>array('hidden'=>true, 'hidedlg'=>true, 'required'=>false),
			//'dp_detailid'=>array('label'=>'dp log', 'hidden'=>true, 'required'=>false, 'formatter'=>'dp_log_link'),
			'isactive'=>array('label'=>'Is Active', 'hidden'=>true),
		);
		$this->getCond();
		$this->getQueryInfo($db, $table);
		$this->options['edit'] = array('d_code', 'ver', 'summary', 'precondition', 'steps', 'expected_result', 'auto_level_id', 'test_env_id', 
			'result_type_id', 'defect_ids', 'comment', 'issue_comment', 'new_issue_comment'=>array('edittype'=>'textarea'), 'duration_minutes');	
			
		$this->options['gridOptions']['label'] = 'cycle cases';
		$this->options['gridOptions']['inlineEdit'] = false;
		$this->options['gridOptions']['search'] = false;
		$this->options['navOptions']['refresh'] = false;
        $this->options['ver'] = '1.0';
    }
	
	protected function getQueryInfo($db, $table){
		$this->options['query'] = array(
			'normal'=>array(
				'key'=>array('label'=>'Keyword'), 'result_type_id', 'tester_id', 'test_env_id', 
				'defect_ids', 'testcase_module_id', 'testcase_testpoint_id',
				'testcase_category_id', 'ver'=>array('excluded'=>true), 'isactive', 'testcase_priority_id', 'auto_level_id',
				'deadline_from'=>array('excluded'=>true, 'type'=>'date', 'label'=>'Deadline From'), 
				'deadline_to'=>array('excluded'=>true, 'type'=>'date', 'label'=>'Deadline To'))//, 'group_by'=>array('label'=>'Group By', 'excluded'=>true))
		);
	
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id'){
			$roleAndStatus = $this->roleAndStatus('cycle', $this->params['cond']['value'], 0, array('status'=>'cycle_status_id', 'group'=>'group_id', 'testers'=>'tester_ids'));
			if(!empty($roleAndStatus['testers']))
				$this->params['sp_testers'] = explode(",", $roleAndStatus['testers']);
		}
		
		if(!empty($roleAndStatus['group'])){
			$group = $roleAndStatus['group'];
			if($group == GROUP_KSDK || $group == GROUP_USB || $group == GROUP_MQX){
				$this->options['query'] = array(
					'normal' =>array('key'=>array('label'=>'Keyword'), 'result_type_id', 'tester_id', 'testcase_module_id',
							'defect_ids', 'build_result_id'=>array('data_source_table'=>'result_type'),'owner_id','testcase_priority_id',
							'testcase_type_id', 'chip_id'=>array('label'=>'Chip', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'chip', 'label'=>'Chip')), 
							'board_type_id'=>array('label'=>'Board', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'board_type', 'label'=>'Board')),
							'os_id'=>array('label'=>'Os', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'os', 'label'=>'Os')),
							
							 'test_env_id','prj_id'=>array('label'=>'Prj', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'Prj')), 
							'compiler_id'=>array('label'=>'Compiler', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'compiler', 'label'=>'Compiler')), 
							'build_target_id'=>array('label'=>'Target', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'build_target', 'label'=>'Target')),
					),
					'advanced'=>array('auto_level_id', 'testcase_category_id', 'ver'=>array('excluded'=>true), 'testcase_testpoint_id', 
						'deadline_from'=>array('excluded'=>true, 'type'=>'date', 'label'=>'Deadline From'), 
						'deadline_to'=>array('excluded'=>true, 'type'=>'date', 'label'=>'Deadline To'))
				);
			}
			elseif($group == GROUP_KIBBLE){
				$this->options['query'] = array(
					'normal' =>array('key'=>array('label'=>'Keyword'), 'result_type_id', 'tester_id', 'test_env_id',
							'defect_ids', 'testcase_module_id', 'testcase_testpoint_id', 'testcase_priority_id',
							'chip_id'=>array('label'=>'Chip', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'chip', 'label'=>'Chip')), 
							'board_type_id'=>array('label'=>'Board', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'board_type', 'label'=>'Board')),
							'os_id'=>array('label'=>'Os', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'os', 'label'=>'Os')),
							'prj_id'=>array('label'=>'Prj', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'Prj')), 
					),
					'advanced'=>array('auto_level_id', 'testcase_category_id', 'ver'=>array('excluded'=>true))
				);
			}
			elseif($group == GROUP_FAS){
				$this->options['query'] = array(
					'normal' =>array('key'=>array('label'=>'Keyword'), 'result_type_id', 'prj_id', 'tester_id', 'test_env_id',
							'defect_ids', 'testcase_module_id', 'testcase_testpoint_id', 'testcase_priority_id' ,
							'auto_level_id', 'testcase_category_id', 'ver'=>array('excluded'=>true)
					)
				);
			}
		}
		
		//$role = $roleAndStatus['role'];
		if(isset($this->params['container'])){
			if($this->params['container'] == 'div_new_case_add'){
				if(!empty($roleAndStatus['group'])){
					$group = $roleAndStatus['group'];
					if($group != 6 && $group != 7 && $group != 10){
						$this->options['query']['normal'] = array('key'=>array('label'=>'Keyword'), 'os_id', 'chip_id', 'board_type_id', 
							'prj_id', 'creater_id'=>array('excluded'=>true), 'cycle_id', 'testcase_type_id', 'testcase_priority_id', 'result_type_id');
					}
					else{
						$this->options['query']['normal'] = array('key'=>array('label'=>'Keyword'), 'os_id', 'chip_id', 'board_type_id', 
							'prj_id'=>array('label'=>'Project', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'Project')), 
							'compiler_id'=>array('label'=>'Compiler', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'compiler', 'label'=>'Compiler')), 
							'build_target_id'=>array('label'=>'Build Target', 'type'=>'single_multi', 'init_type'=>'single', 
								'single_multi'=>array('db'=>$db, 'table'=>'build_target', 'label'=>'Build Target')), 
							'creater_id'=>array('excluded'=>true), 'cycle_id', 'testcase_type_id', 'testcase_priority_id', 'result_type_id');
					}
				}
				$this->options['query']['advanced'] = array('testcase_category_id', 'testcase_module_id', 'testcase_testpoint_id', 'test_env_id',
					'auto_level_id', 'defect_ids', 'tester_id');
			}
			else if(stripos($this->params['container'], "test_history") !== false){
				$this->options['query']['normal'] = 
					array('os_id', 'chip_id', 'board_type_id', 'prj_id', 'compiler_id', 'build_target_id', 'result_type_id', 
						'cycle_id', "rel_id", "defect_ids", 'tester_id');
				unset($this->options['query']['advanced']);
			}
		}
		
		if(isset($roleAndStatus['status'])){
			$status = $roleAndStatus['status'];
			if($status == CYCLE_STATUS_ONGOING){
				$this->options['query']['buttons'] = array(	
					'query_add'=>array('label'=>'Add Cases', 'title'=>'Add Cases To The Cycle'),
					'query_remove'=>array('label'=>'Remove', 'title'=>'Remove Cases From This Cycle')
				);
			}
		}
		
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id'){
			$this->options['query']['additional'] = $this->getStatistics(0);
		}
	}
	
	// private function getEditInfo($db, $table){
	// }
	
	protected function getRoleRow($table_name, $id){
		$row = array();
		if(empty($id)){
// print_r($this->params);
			if(!empty($this->params['cond']) && !empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id' ){
				$res = $this->tool->query("SELECT * FROM cycle WHERE id IN ({$this->params['cond']['value']})");
			}
		}
		else{
			$res = $this->tool->query("SELECT * FROM cycle_detail LEFT JOIN cycle ON cycle.id = cycle_detail.cycle_id WHERE cycle_detail.id IN ($id)");
		}
		if(!empty($res))
			$row = $res->fetch();
		return $row;
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
		$this->_getQuerFields("auto_level_id", "normal", array("edittype"=>"checkbox", "cols"=>"4"));
		$this->_getQuerFields("testcase_priority_id", "normal", array("edittype"=>"checkbox", "cols"=>"6"));
		$this->_getQuerFields("result_type_id", "normal", array("searchoptions"=>array("value"=>";-1:==Blank==")));
		$this->_getQuerFields("build_result_id", "normal", array("searchoptions"=>array("value"=>";-1:==Blank==")));
		$this->_getQuerFields("tester_id", "normal", array("searchoptions"=>array("value"=>";-1:==Blank==")));
		$this->_getQuerFields("creater_id", "normal", array("queryoptions"=>array("value"=>$this->userInfo->id)));
		
		$this->_getQuerFields("ver", "normal", array("edittype"=>"select",
			"searchoptions"=>array("value"=>array('0'=>'', '1'=>'case need to be updated ver', '2'=>'case not belong to this prj'))));
		$this->_getQuerFields("ver", "advanced", array("edittype"=>"select", 
			"searchoptions"=>array("value"=>array('0'=>'', '1'=>'case need to be updated ver', '2'=>'case not belong to this prj'))));

		if(!empty($this->params['cond']['value'])){
			$res = $this->tool->query("select prj_ids, compiler_ids, build_target_ids, creater_id, assistant_owner_id, tester_ids".
				" from cycle where id=".$this->params['cond']['value']);
			$info = $res->fetch();
			$currentUser = $this->userInfo->id;
			$isAdmin = false;
			if(!empty($this->userInfo->isAdmin))
				$isAdmin = true;
			if(!$isAdmin && $info['creater_id'] != $currentUser && $info['assistant_owner_id'] != $currentUser){
				$info['tester_ids'] = explode(",", $info['tester_ids']);
				if(in_array($currentUser, $info['tester_ids'] )){
					if(!empty($this->options['query']['normal']['tester_id']))
						$this->options['query']['normal']['tester_id']['queryoptions']['value'] = $currentUser;
				}
			}
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$info['prj_ids'].'"}]}';
			if(!empty($this->options['query']['advanced']['prj_id']['single_multi']))
				$this->options['query']['advanced']['prj_id']['single_multi']['data'] = json_encode($cart_data);
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$info['compiler_ids'].'"}]}';
			if(!empty($this->options['query']['advanced']['compiler_id']['single_multi']))
				$this->options['query']['advanced']['compiler_id']['single_multi']['data'] = json_encode($cart_data);
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$info['build_target_ids'].'"}]}';
			if(!empty($this->options['query']['advanced']['build_target_id']['single_multi']))
				$this->options['query']['advanced']['build_target_id']['single_multi']['data'] = json_encode($cart_data);
			if($info['prj_ids']){
				$res = $this->tool->query("select group_concat(distinct os_id) as os_ids, group_concat(distinct chip_id) as chip_ids, group_concat(distinct board_type_id) as board_type_ids".
					" from prj where id in ({$info['prj_ids']})");
				$row = $res->fetch();
				$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$row['os_ids'].'"}]}';
				if(!empty($this->options['query']['advanced']['os_id']['single_multi'])){
					$this->options['query']['advanced']['os_id']['single_multi']['data'] = json_encode($cart_data);
				}
				$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$row['chip_ids'].'"}]}';
				if(!empty($this->options['query']['advanced']['chip_id']['single_multi'])){
					$this->options['query']['advanced']['chip_id']['single_multi']['data'] = json_encode($cart_data);
				}
				$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$row['board_type_ids'].'"}]}';
				if(!empty($this->options['query']['advanced']['board_type_id']['single_multi'])){
					$this->options['query']['advanced']['board_type_id']['single_multi']['data'] = json_encode($cart_data);
				}
			}
		}	
		return $this->options['query'];
	}
	
	public function getButtons(){
		$btns = parent::getButtons();
		unset($btns['add']);
		unset($btns['change_owner']);
		unset($btns['activate']);
		unset($btns['inactivate']);
	//	unset($btns['tag']);
	//	unset($btns['removeFromTag']);
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id'){
			$cycle = '';
			$roleAndStatus = $this->roleAndStatus('cycle', $this->params['cond']['value'], 0, array('status'=>'cycle_status_id'));
			//$role = $roleAndStatus['role'];
			if(isset($roleAndStatus['status'])){
				$status = $roleAndStatus['status'];
				if($status == CYCLE_STATUS_ONGOING){
					$newBtns = array(
						'set_result'=>array('caption'=>'Set Result', 'title'=>'Set the test results'),
						'set_build_result'=>array('caption'=>'Set Build Result', 'title'=>'Set the Build results'),
						'set_crid' => array('caption'=>'Set CRID', 'title'=>'set CRID'),
						'set_tester'=>array('caption'=>'Assign Tester', 'title'=>'Set the tester for the cases'),
						'remove_case' => array('caption'=>'Remove Cases', 'title'=>'Delete records in cycle'),
						'update_ver'=> array('caption'=>'Update Ver', 'title'=>'Update The Version To Latest'),
						'update_env'=>array('caption'=>'Add (or Del) Test Env', 'title'=>'Add or Del Env'),
						'set_deadline'=>array('caption'=>'Set Deadline', 'title'=>'Set Deadline for selected cases'),
						'run'=>array('caption'=>'Run', 'title'=>'Run the selected cases'),
					);
					$btns = array_merge($btns, $newBtns);	
				}	
			}
		}
		return $btns;
	}
	
	protected function getCond(){
		$cond['field'] = 'cycle_id';
		if(!empty($this->params['parent'])){
			// if(!empty($this->params['container'])){
				// if(stripos($this->params['container'], 'cycle_detail') !== false || stripos($this->params['container'], 'cycle_stream') !== false)
					// $cond['value'] = $this->params['parent'];
			// }
			// else if($this->params['table'] == 'zzvw_cycle_detail' || $this->params['table'] == 'zzvw_cycle_detail_stream')
			if(!empty($this->params['filters'])){
				$filter = json_decode($this->params['filters']);
// print_r($filter);
				foreach($filter->rules as $k=>$v){
					if($v->field == 'cycle_id')
						$cond['value'] = $v->data;
				}
			}
			else
				$cond['value'] = $this->params['parent'];
		}
		else if(!empty($this->params['cycle_id'])){
			$cond['value'] = $this->params['cycle_id'];
		}
		else if(!empty($this->params['hidden'])){
			$hidden = json_decode($this->params['hidden']);
			foreach($hidden as $k=>$v){
				if($k == $cond['field'])
					$cond['value'] = $v;
			}
		}
		else if(!empty($this->params['filters'])){
			$filter = json_decode($this->params['filters']);
// print_r($filter);
			foreach($filter->rules as $k=>$v){
				if($v->field == 'cycle_id')
					$cond['value'] = $v->data;
			}
		}
		else if(!empty($this->params['id'])){
			if(is_array($this->params['id']))
				$sql = "select cycle_id from ".$this->get('real_table')." where id in (".implode(", ", $this->params['id']).")";
			else
				$sql = "select cycle_id from ".$this->get('real_table')." where id = ".$this->params['id'];
			$res = $this->tool->query($sql);
			if($info = $res->fetch())
				$cond['value'] = $info['cycle_id'];
		}
		$this->params['cond'] = $cond;
	}
	
	public function getOptions($trimed = true, $params = Array()){
		$status = CYCLE_STATUS_FROZEN;
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id'){
			$roleAndStatus = $this->roleAndStatus('cycle', $this->params['cond']['value'], 0, array('status'=>'cycle_status_id'));
			if(isset($roleAndStatus['status']))
				$status = $roleAndStatus['status'];
		}
		parent::getOptions();
		if($status == CYCLE_STATUS_ONGOING){	
			$colModel = $this->options['gridOptions']['colModel'];
			foreach($colModel as $k=>$m){	
				switch($m['name']){
					case 'result_type_id':
						$colModel[$k]['formatter'] = 'resultLink';
						break;
						
					case 'tester_id':
						$colModel[$k]['formatter'] = 'testorLink';
						break;
						
					case 'build_result_id':
						$colModel[$k]['formatter'] = 'bResultLink';
						break;
				}
			}
			$this->options['gridOptions']['colModel'] = $colModel;
		}
		
		unset($this->options['tags']);
		return $this->options;
	}
	
	protected function handleFillOptionCondition(){
		$where = '';
		if(!empty($this->params['searchConditions'])){
			$searchConditions = $this->params['searchConditions'];
			foreach($searchConditions as $condition){
				switch($condition['field']){
					case 'cycle_id':
						$where = $condition;
					case 'testcase_id':
						$where = $condition;
						break;
				}
			}
		}
		else if(!empty($this->params['parent'])){
			$where = array('field'=>'', 'op'=>'=', 'value'=>'');
			if(!empty($this->params['container'])){
				if(stripos($this->params['container'], 'cycle_detail') !== false){
					$where['field'] = 'cycle_id';
					$where['value'] = $this->params['parent'];
				}
				else if(stripos($this->params['container'], 'test_history') !== false){
					$where['field'] = 'testcase_id';
					$where['value'] = $this->params['parent'];
				}
			}
		}
// print_r($this->params);
		if(!empty($where['value']) && !empty($where['field'])){
			$wheres = " where {$where['field']} = {$where['value']}";
			$sql = "SELECT GROUP_CONCAT(distinct cycle_id) AS cycle_id, GROUP_CONCAT(distinct os_id) AS os_id, 
				GROUP_CONCAT(distinct chip_id) AS chip_id, GROUP_CONCAT(distinct board_type_id) AS board_type_id,
				GROUP_CONCAT(distinct prj_id) AS prj_id, GROUP_CONCAT(distinct compiler_id) AS compiler_id, 
				GROUP_CONCAT(distinct build_target_id) AS build_target_id, GROUP_CONCAT(distinct testcase_type_id) AS testcase_type_id, 
				GROUP_CONCAT(distinct test_env_id) AS test_env_id, GROUP_CONCAT(distinct auto_level_id) AS auto_level_id, 
				GROUP_CONCAT(distinct testcase_priority_id) AS testcase_priority_id, GROUP_CONCAT(distinct tester_id) AS tester_id, 
				GROUP_CONCAT(distinct testcase_category_id) AS testcase_category_id, GROUP_CONCAT(distinct testcase_module_id) AS testcase_module_id, 
				GROUP_CONCAT(distinct testcase_testpoint_id) AS testcase_testpoint_id, GROUP_CONCAT(distinct creater_id) AS creater_id,
				GROUP_CONCAT(distinct owner_id) AS owner_id, GROUP_CONCAT(distinct rel_id) AS rel_id
				FROM ".$this->get('table')." $wheres";	
// print_r($sql);
			$res = $this->tool->query($sql);
			if($row = $res->fetch()){
				$condition = array('field'=>'id', 'op'=>'in');
			//	$condition['value'] = $row['cycle_id'];
			//	$this->fillOptionConditions['cycle_id'] = array($condition);
				$condition['value'] = $row['os_id'];
				$this->fillOptionConditions['os_id'] = array($condition);
				$condition['value'] = $row['chip_id'];
				$this->fillOptionConditions['chip_id'] = array($condition);
				$condition['value'] = $row['board_type_id'];
				$this->fillOptionConditions['board_type_id'] = array($condition);
				$condition['value'] = $row['prj_id'];
				$this->fillOptionConditions['prj_id'] = array($condition);
				$condition['value'] = $row['compiler_id'];
				$this->fillOptionConditions['compiler_id'] = array($condition);
				$condition['value'] = $row['build_target_id'];
				$this->fillOptionConditions['build_target_id'] = array($condition);
				$condition['value'] = $row['testcase_type_id'];
				$this->fillOptionConditions['testcase_type_id'] = array($condition);
				$condition['value'] = $row['testcase_priority_id'];
				$this->fillOptionConditions['testcase_priority_id'] = array($condition);
				$condition['value'] = $row['testcase_module_id'];
				$this->fillOptionConditions['testcase_module_id'] = array($condition);
				$condition['value'] = $row['testcase_testpoint_id'];
				$this->fillOptionConditions['testcase_testpoint_id'] = array($condition);
				$condition['value'] = $row['testcase_category_id'];
				$this->fillOptionConditions['testcase_category_id'] = array($condition);
				$condition['value'] = $row['test_env_id'];
				$this->fillOptionConditions['test_env_id'] = array($condition);
				$condition['value'] = $row['auto_level_id'];
				$this->fillOptionConditions['auto_level_id'] = array($condition);
				$condition['value'] = $row['tester_id'];
				$this->fillOptionConditions['tester_id'] = array($condition);
				$condition['value'] = $row['creater_id'];
				$this->fillOptionConditions['creater_id'] = array($condition);
				$condition['value'] = $row['owner_id'];
				$this->fillOptionConditions['owner_id'] = array($condition);
				$condition['value'] = $row['rel_id'];
				$this->fillOptionConditions['rel_id'] = array($condition);
			}
			$condition = array('field'=>'id', 'op'=>'in');
			$c_sql = "select distinct cycle_id from ".$this->get('table')." $wheres";
			$c_res = $this->tool->query($c_sql);
			while($row = $c_res->fetch()){
				$cycle_id[] = $row['cycle_id'];
			}
			if(!empty($cycle_id)){
				$condition['value'] = implode(",", $cycle_id);
				$this->fillOptionConditions['cycle_id'] = array($condition);	
			}
			if($where['field'] == 'cycle_id'){
				$res = $this->tool->query("select tester_ids from cycle where id = ".$where['value']);
				if($info = $res->fetch()){
					$info['tester_ids'] = explode(",", $info['tester_ids']);
					foreach($info['tester_ids'] as $k=>$tester_id){
						if(empty($tester_id))
							unset($info['tester_ids'][$k]);
					}
					$condition['value'] = implode(",", $info['tester_ids']);
					$this->fillOptionConditions['tester_id'] = array($condition);
				}
			}
		}
	}
	
	public function calcSqlComponents($params, $limited = true){
		$components = parent::calcSqlComponents($params, $limited);
		$groups = array(GROUP_KSDK, GROUP_USB, GROUP_MQX, GROUP_KIBBLE);
		if(empty($components['order'])){
			$components['order'] = 'id desc';
			if(!empty($params['parent'])){
				$res = $this->tool->query("SELECT group_id from cycle where id=".$params['parent']);
				if($row = $res->fetch()){
					if(in_array($row['group_id'], $groups))
						$components['order'] = 'chip_id, os_id, d_code, id desc';
				}
			}
		}
		return $components;
	}
	
	protected function getSpecialFilters(){
		$special = array('ver', 'deadline_from', 'deadline_to');
		return $special;
	}
	
	protected function specialSql($special, &$ret){		
		if(!empty($special)){
			foreach($special as $key=>$value){
				if($value['field'] == 'ver'){
					if($value['value'] == 1){// ver need to update
						$ret['main']['from'] .= " LEFT JOIN prj_testcase_ver ON zzvw_cycle_detail.testcase_id=prj_testcase_ver.testcase_id".
							" LEFT JOIN testcase_ver ON testcase_ver.id = prj_testcase_ver.testcase_ver_id";
						$ret['where'] .= " AND zzvw_cycle_detail.prj_id = prj_testcase_ver.prj_id".
							" AND zzvw_cycle_detail.testcase_ver_id != prj_testcase_ver.testcase_ver_id".
							" AND testcase_ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.", ".EDIT_STATUS_GOLDEN.")";//没有挂在该prj上
					}
					else if($value['value'] == 2){//ver not in this prj
						if(!empty($this->params['prj_id'])){
							if(is_array($this->params['prj_id']) && count($this->params['prj_id']) == 1)
								$prj_id = $this->params['prj_id'][0];
							else
								$prj_id = $this->params['prj_id'];
						}
						else{
							// print_r("select prj_ids from cycle where id = ".$this->params['parent']);
							$res = $this->tool->query("select prj_ids from cycle where id = ".$this->params['parent']);
							if($row = $res->fetch()){
								if(stripos($row['prj_ids'], ",") === false)
									$prj_id = $row['prj_ids'];
							}
						}	
						if(!empty($prj_id)){
							$ret['where'] .= " AND zzvw_cycle_detail.testcase_ver_id NOT IN (SELECT testcase_ver_id from prj_testcase_ver where prj_id={$prj_id})".
								" AND zzvw_cycle_detail.prj_id = {$prj_id}";
						}
					}
				}
				else if($value['field'] == 'deadline_from'){
					if($value['value'] != 'blank'){
						$ret['where'] .= " AND (zzvw_cycle_detail.deadline > '{$value['value']}' OR zzvw_cycle_detail.deadline = '{$value['value']}')";
					}
					else{
						$ret['where'] .= " AND (zzvw_cycle_detail.deadline IS NULL OR zzvw_cycle_detail.deadline = 0)";
					}
				}
				else if($value['field'] == 'deadline_to'){
					if($value['value'] != 'blank'){
						$ret['where'] .= " AND (zzvw_cycle_detail.deadline < '{$value['value']}' OR zzvw_cycle_detail.deadline = '{$value['value']}')";
					}
					else{
						$ret['where'] .= " AND (zzvw_cycle_detail.deadline IS NULL OR zzvw_cycle_detail.deadline = 0)";
					}
				}
			}
		}
	}
	
	protected function getLogs($log){
		$log = json_decode($log, true);
		if(!empty($log)){
			$logFileList = array();
			foreach($log as $key=>$fileInfo){
				if(empty($fileInfo))
					continue;
				$res = $this->tool->query("SELECT server, directory, is_url FROM log_key WHERE id={$key} LIMIT 1");
				if($info = $res->fetch()){
					foreach($fileInfo as $filename){
						if(stripos("/", $filename) === 0 || stripos("\\", $filename) === 0)
							$filename = substr($filename, 0, 1);
						switch($info["server"]){
							case "umbrella":
								$logFileList[] = $filename.",".$info['is_url'].",".basename($filename);
								break;
							case "dapeng":
								$logFileList[] = "http://".$info["server"]."/".$info["directory"]."/".$filename."/None/None/None/".",".$info['is_url'].",Dapeng Log";
								break;
							default:
								$logFileList[] = "http://".$info["server"]."/".$info["directory"]."/".$filename.",".$info['is_url'].",".basename($filename);
								break;
						}
					}
				}
				else
					continue;
			}
			$log = implode(";", $logFileList);
		}
		else
			$log = "";
		return $log;
	}
	
	public function getMoreInfoForRow($row){
		$row['c_f'] = 0;
		$row['isTester'] = false;
		if($row['tester_id'] == $this->userInfo->id)
			$row['isTester'] = true;
		else if(!empty($this->params['role']) && $this->params['role'] == 'row_owner')
			$row['isTester'] = true;
		else if($row['creater_id'] == $this->userInfo->id || (isset($row['assistant_owner_id']) && $row['assistant_owner_id'] == $this->userInfo->id))
			$row['isTester'] = true;
		else if($row['tester_id'] == TESTER_DP && $row['result_type_id'] !=RESULT_TYPE_BLANK && !empty($this->params['sp_testers']) && in_array($this->userInfo->id, $this->params['sp_testers']))
			$row['isTester'] = true;
		else {
			if(in_array('admin', $this->userInfo->roles) || in_array('assistant_admin', $this->userInfo->roles))
				$row['isTester'] = true;
		}
		$row['logs'] = $this->getLogs($row['logs']);
		return $row;
	}
	
	public function getStatistics( $streamDetail = False ){
		if(empty($this->params['cond']['value']))
			return null;
		
		$blank = 0;
		$additional = False;
		$linuxAdditional = False;
		$caseLists = array();
		$row = array('total_cases'=>0, 'pass_cases'=>0, 'fail_cases'=>0, 'finished_cases'=>0, 'nt_cases'=>0, 'na_cases'=>0, 
			'ns_cases'=>0, 'nso_cases'=>0, 'ose_cases'=>0, 'rse_cases'=>0, 'die_cases'=>0, 'ifi_cases'=>0, 'timeout_cases'=>0,
			'abort_cases'=>0);
		$row['parent'] = $this->params['cond']['value'];	
		
		$res = $this->tool->query("SELECT creater_id, assistant_owner_id, group_id FROM cycle WHERE id={$row['parent']}");
		$cycleInfo = $res->fetch();
		if(in_array($cycleInfo['group_id'], array(GROUP_KSDK, GROUP_USB)))
			$additional = True;
		elseif($cycleInfo['group_id'] == GROUP_LINUXBSP)
			$linuxAdditional = True;
		
		//deadline cases count
		$weekend = date("Y-m-d",strtotime('this Sunday'));
		$sql = "SELECT COUNT(*) AS deadline_cases FROM cycle_detail WHERE cycle_id = {$row['parent']} AND result_type_id = ".RESULT_TYPE_BLANK;
		if( $cycleInfo['creater_id'] != $this->userInfo->id && $cycleInfo['assistant_owner_id'] != $this->userInfo->id)
			$sql .= " AND tester_id = {$this->userInfo->id}";
		$sql .= " AND deadline > 0 AND deadline < '".$weekend."'";
		$res = $this->tool->query($sql);
		if($info = $res->fetch()){
			$row['deadline_cases'] = $info['deadline_cases'];
		}
		
		//case result count
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
								case RESULT_TYPE_TIMEOUT:
									$row['timeout_cases'] += $count;
									break;
								case RESULT_TYPE_ABORT:
									$row['abort_cases'] += $count;
									break;
							}
							if($additional){
								switch($resultID){
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
		$statistics = '<strong>Cycle Status: (Unfinished:'.$row['unfinished_cases'].' Finished:'.$row['finished_cases']." Pass:".$row['pass_cases'].
			' Fail:<span style="color:red"> '.$row['fail_cases'].'</span>';
		if(!$streamDetail){
			$statistics .= " Timeout:$blue".$row['timeout_cases']."</span> Abort:$blue".$row['abort_cases']."</span> NT:$blue".$row['nt_cases']."</span> NA:$blue".$row['na_cases']."</span> NS:$blue".$row['ns_cases'];
		}
		if($additional){
			$statistics .= "</span> No Serial Output:$blue".$row['nso_cases']."</span> Open Serial Err:$blue".$row['ose_cases'].
				"</span> Download Image Err:$blue".$row['die_cases']."</span> Interact File Issue:$blue".$row['ifi_cases'].
				"</span> RW Serial Err:$blue".$row['rse_cases'];
		}
		$statistics .= '</span>)';
		if(!empty($row['deadline_cases'])){
			$statistics .= " --- Your <span style='color:red'>{$row['deadline_cases']}</span> cases are close to deadline this week.";
		}
		$statistics .= "</strong>";
		return $statistics;
	}
}

?>