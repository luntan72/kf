<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/zzvw_cycle_detail.php');

class xt_zzvw_cycle_detail_stream extends xt_zzvw_cycle_detail{
	protected function init($params){
        parent::init($params);
		$this->options['linktype'] = 'infoLink';
		$this->options['real_table'] = 'cycle_detail';
        $this->options['list'] = array(
			'id'=>array('formatter'=>'infoLink'),
			'c_f'=>array('hidden'=>true, 'excluded'=>true, 'edittype'=>'text', 'hidedlg'=>true, 'editable'=>false),
			'cycle_id'=>array('hidden'=>true),
			// 'codec_stream_id'=>array('label'=>'S-ID', 'formatoptions'=>array('newpage'=>true)),
			'd_code'=>array('label'=>'S-ID', 'editable'=>false, 'unique'=>false, 'formatter'=>'text_link', 'formatoptions'=>array('db'=>'xt', 'table'=>'codec_stream', 'newpage'=>true, 'data_field'=>'codec_stream_id')),
			'prj_id'=>array('label'=>'Project'),
			'name'=>array('label'=>'Name', 'editable'=>false, 'unique'=>false, 'width'=>65, 'formatter'=>'text', 'formatoptions'=>array('db'=>'xt', 'table'=>'codec_stream', 'newpage'=>true, 'data_field'=>'codec_stream_id')),
			'location'=>array('label'=>'Location', 'width'=>100, 'editable'=>false),
			'test_env_id'=>array('label'=>'Test-Env', 'width'=>50, 'editrules'=>array('required'=>true), 'hidden'=>true),
			'result_type_id'=>array('label'=>'Result', 'width'=>30, 'editrules'=>array('required'=>true)),
			'codec_stream_result'=>array('label'=>'S-Res', 'excluded'=>true, 'width'=>80, 'search'=>false, 'editable'=>false),	
			'comment'=>array('label'=>'CR Comment', 'width'=>100),
			'issue_comment'=>array('label'=>'Issue Comment', 'hidden'=>true, 'editable'=>false),		
			'defect_ids'=>array('label'=>'CRID/JIRA Key', 'formatter'=>'text', 'editrules'=>array('required'=>false), 'width'=>50),			
			'testcase_priority_id'=>array('label'=>'Priority', 'cols'=>6, 'editable'=>false),
			'codec_stream_type_id'=>array('label'=>'Type', 'hidden'=>true, 'editable'=>false),
			'codec_stream_format_id'=>array('label'=>'Format', 'editable'=>false),
			'duration_minutes'=>array('hidden'=>true),
			'precondition'=>array('label'=>'Precondition', 'hidden'=>true, 'rows'=>8, 'editable'=>false),
			'steps'=>array('label'=>'Steps', 'hidden'=>true, 'rows'=>8, 'editable'=>false),
			'command'=>array('label'=>'CMDline', 'hidden'=>true, 'editable'=>false, 'excluded'=>true),
			'duration'=>array('label'=>'Duration', 'hidden'=>true, 'width'=>35),
			'a_duration'=>array('label'=>'Audio Duration', 'width'=>35, 'hidden'=>true),
			'v_duration'=>array('label'=>'Video Duration', 'width'=>35, 'hidden'=>true),
			'deadline'=>array('hidden'=>true, 'required'=>false),
			'finish_time'=>array('label'=>'Finished', 'width'=>70),
			'tester_id'=>array('label'=>'Testor', 'width'=>35),
			'isTester'=>array('excluded'=>true, 'hidden'=>true, 'hidedlg'=>true),
			'logs'=>array('hidden'=>true, 'editoptions'=>array('defval'=>'logs'), 'formatter'=>'log_link'),
			//'log_link'=>array('hidden'=>true),
		);
		
		$this->getQueryInfo($db, $table);
		$this->options['edit'] = array('d_code', 'name', 'testcase_priority_id','precondition', 'steps', 'test_env_id', 'result_type_id', 
			'defect_ids', 'comment', 'issue_comment', 'new_issue_comment'=>array('edittype'=>'textarea'), 'duration_minutes');		
			
		$this->options['gridOptions']['label'] = 'cycle cases';
		$this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'id', 'db'=>'xt', 'table'=>'zzvw_subgrid_cycle_detail');
		$this->options['gridOptions']['inlineEdit'] = false;
		$this->options['gridOptions']['search'] = false;
		$this->options['navOptions']['refresh'] = false;
        $this->options['ver'] = '1.0';
		$this->getCond();
    }
	
	protected function getQueryInfo($db, $table){
		$this->options['query'] = array(
			'cols'=>4,			
			'normal'=>array('key'=>array('label'=>'Keyword'), 'result_type_id', 'tester_id', 
				'codec_stream_format_id'=>array('label'=>'Format', 'type'=>'single_multi', 'init_type'=>'single', 
					'single_multi'=>array('db'=>$db, 'table'=>'codec_stream_format', 'label'=>'Format')),
				'defect_ids', 'test_env_id', 'codec_stream_type_id', 'prj_id', 'testcase_priority_id')
		);
// print_r($this->params);
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id')
			$roleAndStatus = $this->roleAndStatus('cycle', $this->params['cond']['value'], 0, array('status'=>'cycle_status_id'));
		if(isset($roleAndStatus['status'])){
			$status = $roleAndStatus['status'];
			if($status == CYCLE_STATUS_ONGOING){
				$this->options['query']['buttons'] = array(
					'query_add'=>array('label'=>'Add Cases', 'title'=>'Add Cases To The Cycle'),
					'query_remove'=>array('label'=>'Remove', 'title'=>'Remove Cases From This Cycle'),
					'query_update'=>array('label'=>'Update All Tricmodes', 'title'=>'Update All Trickmodes For this Cycle')
				);
			}
		}
		if(!empty($this->params['container'])){
			if($this->params['container'] == 'div_new_case_add'){
				$this->options['query']['normal'] = array('key'=>array('label'=>'Keyword'), 'os_id', 'chip_id', 'board_type_id', 
					'prj_id', 'creater_id'=>array('excluded'=>true), 'cycle_id', 'codec_stream_type_id', 'codec_stream_format_id', 'tester_id', 'testcase_priority_id', 'result_type_id');
				unset($this->options['query']['advanced']);
			}
			else if(stripos($this->params['container'], "test_history") !== false){
				$this->options['query']['normal'] = array('key'=>array('label'=>'Keyword'), 'os_id', 'chip_id', 'board_type_id', 
					'prj_id', 'creater_id'=>array('excluded'=>true), 'cycle_id', 'tester_id', 'result_type_id');
			}
		}
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id'){
			$this->options['query']['additional'] = $this->getStatistics(True);
		}
	}
	
	protected function getQueryFields($params = array()){	
		parent::getQueryFields($params);
		if(!empty($this->options['query']['normal']['testcase_priority_id'])){
			$this->options['query']['normal']['testcase_priority_id']['edittype'] = 'checkbox';
			$this->options['query']['normal']['testcase_priority_id']['cols'] ='6';
		}
		if(!empty($this->params['cond']['value'])){
			$res = $this->tool->query("select group_concat(distinct codec_stream_format_id) as codec_stream_format_ids from zzvw_cycle_detail_stream where cycle_id=".$this->params['cond']['value']);
			$info = $res->fetch();
			$cart_data = new stdClass;
			$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$info['codec_stream_format_ids'].'"}]}';
			if(!empty($this->options['query']['normal']['codec_stream_format_id']['single_multi']))
				$this->options['query']['normal']['codec_stream_format_id']['single_multi']['data'] = json_encode($cart_data);
		}
		return $this->options['query'];
	}
	
	public function getButtons(){
		$btns = parent::getButtons();
		if(!empty($this->params['cond']['value']) && $this->params['cond']['field'] == 'cycle_id'){
			$roleAndStatus = $this->roleAndStatus('cycle', $this->params['cond']['value'], 0, array('status'=>'cycle_status_id'));
//				$roleAndStatus = $this->roleAndStatus($cond['value']);
			$role = $roleAndStatus['role'];
			if(isset($roleAndStatus['status'])){
				$status = $roleAndStatus['status'];
				if($status == CYCLE_STATUS_ONGOING){
					unset($btns['update_ver']);
					$btns['update_trickmode'] = array('caption'=>'Add (or Del) TrickModes', 'title'=>'Add or Del TrickModes');
				}
			}				
			if(!empty($btns['set_build_result']))
				unset($btns['set_build_result']);
		}
		return $btns;
	}
	
	private function getStreamRow(&$row){
		// if use group_concat and there is fail, result_type will be empty
		// I think this is reaonable that if all pass, then input original value
		$res = $this->tool->query("SELECT id, name FROM result_type");
		$result_type[0] = 'Blank';
		while($info = $res->fetch())
			$result_type[$info['id']] = $info['name'];
		$res0 = $this->tool->query("SELECT comment, defect_ids, result_type_id, finish_time".
			" FROM cycle_detail WHERE cycle_id=".$row['cycle_id']." AND codec_stream_id=".$row['codec_stream_id'].
			" AND test_env_id=".$row['test_env_id']." AND prj_id=".$row['prj_id'].
			" AND build_target_id=".$row['build_target_id']." AND compiler_id=".$row['compiler_id']);
		$row['defect_ids'] = null;
		while($info = $res0->fetch()){
			$d['result_type_id'][] = $info['result_type_id'];
			if(!empty($info['defect_ids']) && $info['defect_ids'] != '')
				$d['defect_ids'][] = $info['defect_ids'];
			if(!empty($info['comment']) && $info['comment'] != '')
				$d['comment'][] = $info['comment'];
			if(!empty($info['finish_time']) && $info['finish_time'] != '')
				$d['finish_time'][] = $info['finish_time'];
		}
		if(isset($d['comment'])){
			$d['comment'] = array_unique($d['comment']);
			if(count($d['comment']) == 1)
				$row['comment'] = $d['comment'][0];
			else
				$row['comment'] = 'Pls Check Specific Trickmode Comment In Subgrid';
		}
		if(isset($d['finish_time'])){
			$d['finish_time'] = array_unique($d['finish_time']);
			if(count($d['finish_time']) == 1)
				$row['finish_time'] = $d['finish_time'][0];
			else{
				foreach($d['finish_time'] as $key=>$finsh_time){
					if(0 == $key)
						$time = $d['finish_time'][0];
					else if($time < $finsh_time){
						$time = $finsh_time;
					}
				}
				$row['finish_time'] = $time;
			}
		}
		if(isset($d['defect_ids']))
			$row['defect_ids'] = implode(", ", array_unique($d['defect_ids']));
		if(!empty($d['result_type_id'])){
			$d['result_type_id'] = array_unique($d['result_type_id']);
			if(count($d['result_type_id']) == 1){
				$row['codec_stream_result'] = 'All '.$result_type[$d['result_type_id'][0]];
				if($d['result_type_id'][0] == RESULT_TYPE_FAIL)
					$row['result_type_id'] = 250;
			}
			else{
				// $d['result_type_id'] = explode(",", $d['result_type_id']);
// print_r($d['result_type_id']);
				unset($result_type['1']);
				$special = array('0'=>'Blank', '2'=>'Fail');
				foreach($result_type as $k=>$v){
// print_r($k);
					if(in_array($k, $d['result_type_id'])){
						$row['result_type_id'] = 100 + $k;// Testing
						$row['codec_stream_result'] = 'Has '.$v;
						if(in_array(RESULT_TYPE_PASS, $d['result_type_id']))
							$row['codec_stream_result'] = 'Has '.$v.' & Pass';
						if($k != '2'){
							if(in_array(RESULT_TYPE_FAIL, $d['result_type_id']))
								$row['codec_stream_result'] = 'Has '.$v.' & Fail';
						}
						if($k == 0 || $k = 2)
							break;
					}
				}
			}
		}
		$res = $this->tool->query("select env_item_ids from test_env where id=".$row['test_env_id']);
// print_r($row['test_env_id']."zzz");
		if($info = $res->fetch()){
			$env_item = false;
			if(!empty($info['env_item_ids'])){
				$res0 = $this->tool->query("select steps, precondition, command from stream_steps".
					" where codec_stream_type_id=".$row['codec_stream_type_id']." and env_item_id in (".$info['env_item_ids'].")");
				if($info0 = $res0->fetch()){		
					$env_item = true;
					$row['steps'] = $info0['steps'];
					$row['precondition'] = $info0['precondition'];
					$row['command'] = $info0['command'];
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
						$row['steps'] = $info1['steps'];
						$row['precondition'] = $info1['precondition'];
						$row['command'] = $info1['command'];
					}
				}
			}
		}
	}
	
	public function getMoreInfoForRow($row){
		$row['c_f'] = 1;
		$row['codec_stream_result'] = 'does not exsit';
		if(isset($row['c_f']) && $row['c_f'] == 1){
			$this->getStreamRow($row);
		}
		$row['isTester'] = false;
		if($row['tester_id'] == $this->userInfo->id)
			$row['isTester'] = true;
		else if($this->params['role'] ==  'row_owner')
			$row['isTester'] = true;
		else if($row['creater_id'] == $this->userInfo->id)
			$row['isTester'] = true;
		else {
			if(in_array('admin', $this->userInfo->roles) || in_array('assistant_admin', $this->userInfo->roles))
				$row['isTester'] = true;

		}
		$row['logs'] = "";
		return $row;
	}
	
	protected function handleFillOptionCondition(){
		$where = '';
		if(!empty($this->params['searchConditions'])){
			$searchConditions = $this->params['searchConditions'];
			foreach($searchConditions as $condition){
				switch($condition['field']){
					case 'cycle_id':
						$where = $condition;
					case 'codec_stream_id':
						$where = $condition;
						break;
				}
			}
		}
		else if(!empty($this->params['parent'])){
			$where = array('field'=>'', 'op'=>'=', 'value'=>'');
			if(!empty($this->params['container'])){
				if(stripos($this->params['container'], 'cycle_stream') !== false){
					$where['field'] = 'cycle_id';
					$where['value'] = $this->params['parent'];
				}
				else if(stripos($this->params['container'], 'test_history') !== false){
					$where['field'] = 'codec_stream_id';
					$where['value'] = $this->params['parent'];
				}
			}
		}
		if(!empty($where['value']) && !empty($where['field'])){
			$wheres = " where {$where['field']} = {$where['value']}";
			$sql = "select group_concat(distinct os_id) as os_id, 
				group_concat(distinct chip_id) as chip_id, group_concat(distinct board_type_id) as board_type_id,
				group_concat(distinct prj_id) as prj_id, group_concat(distinct compiler_id) as compiler_id, 
				group_concat(distinct build_target_id) as build_target_id, group_concat(distinct codec_stream_type_id) as codec_stream_type_id, 
				group_concat(distinct test_env_id) as test_env_id, group_concat(distinct codec_stream_format_id) as codec_stream_format_id, 
				group_concat(distinct testcase_priority_id) as testcase_priority_id, group_concat(distinct tester_id) as tester_id,
				group_concat(distinct creater_id) as creater_id
				from ".$this->get('table')." $wheres";			
			$res = $this->tool->query($sql);
			if($row = $res->fetch()){
				$condition = array('field'=>'id', 'op'=>'in');
				//$condition['value'] = $row['cycle_id'];
				//$this->fillOptionConditions['cycle_id'] = array($condition);
				$condition['value'] = $row['os_id'];
				$this->fillOptionConditions['os_id'] = array($condition);
				$condition['value'] = $row['chip_id'];
				$this->fillOptionConditions['chip_id'] = array($condition);
				$condition['value'] = $row['board_type_id'];
				$this->fillOptionConditions['board_type_id'] = array($condition);
				$condition['value'] = $row['prj_id'];
				$this->fillOptionConditions['prj_id'] = array($condition);
				// $condition['value'] = $row['compiler_id'];
				// $this->fillOptionConditions['compiler_id'] = array($condition);
				// $condition['value'] = $row['build_target_id'];
				// $this->fillOptionConditions['build_target_id'] = array($condition);
				$condition['value'] = $row['testcase_priority_id'];
				$this->fillOptionConditions['testcase_priority_id'] = array($condition);
				$condition['value'] = $row['codec_stream_type_id'];
				$this->fillOptionConditions['codec_stream_type_id'] = array($condition);
				$condition['value'] = $row['codec_stream_format_id'];
				$this->fillOptionConditions['codec_stream_format_id'] = array($condition);
				$condition['value'] = $row['tester_id'];
				$this->fillOptionConditions['tester_id'] = array($condition);
				$condition['value'] = $row['test_env_id'];
				$this->fillOptionConditions['test_env_id'] = array($condition);
				$condition['value'] = $row['creater_id'];
				$this->fillOptionConditions['creater_id'] = array($condition);
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
}

?>