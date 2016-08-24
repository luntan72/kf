<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_action_set_result extends xt_zzvw_cycle_detail_action_jqgrid{

	public function handlePost(){
		//$params = $this->parseParams();	
		$params = $this->params;
		$element = $this->caclIDs($params);
		if($params['select_item'] == '-1')
			$params['select_item'] = 0;
		if($element == "error")
			return "error";
		$res = $this->tool->query("SELECT creater_id, assistant_owner_id, tester_ids FROM cycle WHERE id = ".$params['parent']);
		$info = $res->fetch();
		$currentUser = $this->userInfo->id;
		$is_Tester = false;
		if(in_array($currentUser, explode(",", $info['tester_ids']))){
			$is_Tester = true;
		}
		$res = $this->tool->query("SELECT id, result_type_id, tester_id, defect_ids, updater_id FROM cycle_detail WHERE id in (".implode(',', $element).")");
		$i = 0;
		
// print_r($this->userInfo->roles);
		$isAdmin = false;
		if(in_array('admin', $this->userInfo->roles))
			$isAdmin = true;
		$date = date("Y-m-d H:i:s");
		$data = array('result_type_id'=>$params['select_item']);
		$finish_time = $date;
		if(!$params['select_item'])
			$finish_time = '0000-00-00 00:00:00';
		if(isset($params['comment']) && isset($params['defect_ids'])){
		$data['comment'] = $data['defect_ids'] = null;
			if(!empty($params['comment']))
				$data['comment'] = $this->userInfo->nickname.":".mysql_real_escape_string($params['comment']);
			if(!empty($params['defect_ids']))	
				$data['defect_ids'] = $params['defect_ids'];
		}
		while($row = $res->fetch()){
			if($row['tester_id'] == $currentUser || $info['creater_id'] == $currentUser|| $info['assistant_owner_id'] == $currentUser || $isAdmin
				|| ($row['tester_id'] == TESTER_DP && $is_Tester)){
				if(!empty($data['defect_ids'])){
					// $defect_ids = $data['defect_ids'];
					// if(empty($row['defect_ids'])){
						// $data['defect_ids'] = $params['defect_ids'];
					// }
					// else{
						// $keys = explode(",", $row['defect_ids']);
						// if(in_array($params['defect_ids'], $keys))
							// $data['defect_ids'] = $row['defect_ids'];
						// else
							// $data['defect_ids'] = $row['defect_ids'].",".$params['defect_ids'];
					// }
					$data['jira_key_ids'] = $data['defect_ids'];
				}
				$data['updater_id'] = $this->userInfo->id;
				$data['tester_id'] = $row['tester_id'];
				if(($isAdmin || $info['creater_id'] == $currentUser || $info['assistant_owner_id'] == $currentUser) && 0 == $row['tester_id'])
					$data['tester_id'] = $currentUser;
				if($data['result_type_id'] != $row['result_type_id'])
					$data['finish_time'] = $finish_time;
				else if(!empty($data['finish_time']))
					unset($data['finish_time']);
				$this->tool->update('cycle_detail', $data, "id=".$row['id']);
				if(!empty($defect_ids))
					$data['defect_ids'] = $defect_ids;
				if($params['select_item'] && $data['result_type_id'] != $row['result_type_id'])
					$this->tool->updatelastresult($row['id']);	
				$i ++;
			}
		}
		if($i == count($element)){
			//$params['id'] = json_decode($params['id']);
			$ret_data = array('result_type_id'=>$params['select_item'], 'finish_time'=>$date, 'id'=>$params['id'][0],
				'comment'=>'null', 'issue_comment'=>'null', 'defect_ids'=>'null');
			if(!empty($params['comment'])){
				$author = $this->userInfo->nickname;
				$ret_data['comment'] = $author.":".$params['comment'];
			}
			if(!empty($params['defect_ids']))
				$ret_data['defect_ids'] = $params['defect_ids'];
			$ret_data['updater_id'] = $this->userInfo->id;;
			if(!empty($data['tester_id']))
				$ret_data['tester_id'] = $data['tester_id'];
			return $this->returnData($ret_data);
		}
	}
	
	protected function returnData($data){
		$datas['statistics'] = $this->getStatistics();
		$datas['data'] = $data;
		return json_encode($datas);
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Result';
		$view_params['view_file'] = 'newElement.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
		$res = $this->tool->query("select id, name from result_type");
		while($row = $res->fetch())
			$result_type[$row['id']] = $row['name'];
		$result_type['-1'] = '==Blank==';
		$view_params['cols'] = array(
			array('id'=>'select_item', 'name'=>'select_item', 'label'=>'Result', 'editable'=>true, 'DATA_TYPE'=>'int', 'type'=>'select', 'editoptions'=>array('value'=>$result_type), 'editrules'=>array('required'=>true)),
			array('id'=>'comment', 'name'=>'comment', 'label'=>'CR Comment', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'textarea'),
			array('id'=>'defect_ids', 'name'=>'defect_ids', 'label'=>'CRID', 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'text'),
		);
		$res = $this->tool->query("SELECT group_id FROM cycle WHERE id = {$params['parent']} LIMIT 1");
		if($row = $res->fetch()){
			if(GROUP_KSDK == $row['group_id'] || GROUP_USB == $row['group_id'])
				$view_params['cols'][0]['defval'] = 2;
		}
		return $view_params;
	}
	
}

?>