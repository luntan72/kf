<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_information.php');

class xt_zzvw_cycle_action_information extends action_information{
	protected function buttons($oper){
		//$params = $this->parseParams();
		$params = $this->params;
		//$params['id'] = json_decode($params['id']);
		$view_params['id'] = isset($params['id']) ? $params['id'] : (isset($params['id']) ? $params['id'] : 0);
		if (is_array($view_params['id']))
			$view_params['id'] = implode(',', $view_params['id']);
		$view_buttons = $this->getViewEditButtons($view_params);
		if($oper == 'freeze')
			unset($view_buttons['view_edit_edit']);
// print_r($view_buttons);
		$view_params = array('btn'=>$view_buttons, 'editable'=>true);
		$view_params['view_file_dir'] = 'xt/zzvw_cycle/view';
		$this->renderView('button_edit.phtml', $view_params);
	}
	
	protected function paramsFor_view_edit($params){
		$view_params = parent::paramsFor_view_edit($params);
		return $view_params;
	}
	
	protected function paramsFor_cycle_detail($params){
		$view_params['view_file_dir'] = 'xt/zzvw_cycle/view';
		$view_params['label'] = 'Cycle Detail';
		$view_params['disabled'] = !$params['id'];
		$view_params['id'] = $params['id'];	
		return $view_params;
	}
	
	protected function paramsFor_cycle_stream($params){
		$view_params = array();
		$res = $this->tool->query("select group_id, testcase_type_ids from cycle where id=".$params['id']);
		if($info = $res->fetch()){	
			if(GROUP_CODEC == $info['group_id'] || GROUP_FAS == $info['group_id']){// && $info['testcase_type_id'] == TESTCASE_TYPE_CODEC)
				if(TESTCASE_TYPE_CODEC == $info['testcase_type_ids'] || TESTCASE_TYPE_FAS == $info['testcase_type_ids']){
					$view_params['view_file_dir'] = 'xt/zzvw_cycle/view';
					$view_params['label'] = 'Cycle Stream';
					$view_params['disabled'] = !$params['id'];
					$view_params['id'] = $params['id'];
				}
			}
		}
		return $view_params;
	}
	
	protected function paramsFor_cycle_overnight($params){
		$view_params = array();
		$res = $this->tool->query("select group_id, testcase_type_ids from cycle where id=".$params['id']);
		if($info = $res->fetch()){
			if(GROUP_LINUXBSP == $info['group_id']){
				if(TESTCASE_TYPE_LINUX_BSP == $info['testcase_type_ids']){
					$data = array();
					$needUpdate = false;
					$newRes = $this->tool->query("SELECT testcase.code, testcase.summary, detail.tester_id, result_type.name as result,".
						" detail.result_type_id, detail.deadline, detail.comment, detail.defect_ids, detail.finish_time FROM cycle_detail detail".
						" LEFT JOIN testcase ON testcase.id=detail.testcase_id".
						" LEFT JOIN result_type ON result_type.id=detail.result_type_id".
						" WHERE detail.cycle_id={$params['id']} AND testcase.summary LIKE '%overnight%'".
						" ORDER BY testcase.code asc");
					$testers = array();
					while($row = $newRes->fetch()){
						if(!isset($testers[$row["tester_id"]]) && $row["tester_id"] != 0){
							$res0 = $this->log_db->query("SELECT nickname FROM users where id={$row["tester_id"]}");
							if($tester = $res0->fetch())
								$testers[$row["tester_id"]] = $tester['nickname'];
						}
						if($row['tester_id'] != 0)
							$row['tester_id']= $testers[$row["tester_id"]];
						else
							$row['tester_id'] = "";
						if($row['result_type_id'] == 0)
							$needUpdate = true;
						$data[] = $row;
					}
					if(!empty($data)){
						$view_params['view_file_dir'] = 'xt/zzvw_cycle/view';
						$view_params['label'] = 'Cycle OverNight';
						$view_params['disabled'] = !$params['id'];
						$view_params['id'] = $params['id'];
						$view_params['data'] = $data;
						$view_params['needUpdate'] = $needUpdate;
					}
				}
			}
		}	
// print_r($view_params);
		return $view_params;
	}
	
	protected function getViewEditButtons($params){
		$btns = parent::getViewEditButtons($params);
		unset($btns['view_edit_saveandnew']);
		if (!empty($params['id'])){
			$roleAndStatus = $this->table_desc->roleAndStatus('cycle', $params['id'], 0, array('status'=>'cycle_status_id', 
				'assistant_owner'=>'assistant_owner_id', 'dp_req'=>'zzvw_mcuauto_request_ids', 'group'=>'group_id'));
// print_r($roleAndStatus);
			$role = $roleAndStatus['role'];
			$user_roles = $this->getRoles();
			$status = $roleAndStatus['status'];
			$group = $roleAndStatus['group'];
			$style = 'position:relative;float:left';
			$display = $style;
			$hide = $style.';display:none';	
			$newBtns = array(
				'unfreeze'=>array('label'=>'unFreeze', 'title'=>'Unfreeze This Cycle', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $hide : $style),
				'inside_freeze'=>array('label'=>'Freeze', 'title'=>'Freeze This Cycle', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $style : $hide),
				'uploadfile' => array('label'=>'Upload', 'title'=>'Import File to Cycle', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $style : $hide),
				'view_edit_export' => array('style'=>$style, 'label'=>'Export'),
				'run' => array('style'=>$style, 'label'=>'Run'),
				'stop' => array('style'=>$style, 'label'=>'Stop')
			);
			
			if(!empty($roleAndStatus['dp_req'])){
				$newBtns['download'] = array('label'=>'Download', 'title'=>'Download', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $style : $hide);
				$roles = array('admin', 'owner');
				if(in_array($role, $roles))
					$newBtns['update_dp'] = array('label'=>'Update Dapeng', 'title'=>'Update Dapeng', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $style : $hide);
			}
			if($group == 6 || $group == 7 || $group == 10){
				$newBtns['remove_combination'] = array('label'=>'Remove Combination', 'title'=>'Remove Cases From Cycle With Certain Conditions', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $style : $hide);
			}
			if($group == 1){
				$newBtns['update'] = array('label'=>'Update From Other Cycle', 'title'=>'Update result from other cycle', 'style'=>($status == CYCLE_STATUS_ONGOING) ? $style : $hide);
			}
			$res = $this->tool->query("select * from cycle where id={$params['id']}");
			$cycle = $res->fetch();
			if($cycle['request_status_id'] == REQUEST_STATUS_WAITING || $cycle['request_status_id'] == REQUEST_STATUS_RUNNING){
				unset($newBtns['run']);
			}
			else
				unset($newBtns['stop']);
			$display_roles = array('admin', 'af_tester');
			$dif = array_intersect($display_roles, $user_roles);
// print_r($user_roles);
// print_r($dif);			
// print_r($newBtns);
			if(empty($dif)){
				unset($newBtns['run']);
				unset($newBtns['stop']);
			}
// print_r($newBtns);			
			$btns = array_merge($btns, $newBtns);
			if($status == CYCLE_STATUS_FROZEN){
				unset($btns['view_edit_edit']);
				unset($btns['run']);
				unset($btns['stop']);
			}
		}
// print_r($btns);		
		return $btns;
	}
}
?>