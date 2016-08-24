<?php
require_once('action_jqgrid.php');

class xt_os_action_linkage extends action_jqgrid{
	protected function handlePost(){
		$sql = "SELECT distinct os.id, os.name FROM os LEFT JOIN groups_os ON groups_os.os_id=os.id";
		$where = "groups_os.groups_id in (".$this->userInfo->group_ids.")";
		$left_join = "";
		if(!empty($this->params['value'])){
			if($this->params['field'] == 'testcase_type_ids'){
				$left_join = " LEFT JOIN os_testcase_type ON os_testcase_type.os_id=os.id";
				$where .= " AND os_testcase_type.testcase_type_id in (".$this->params['value'].")";
			}
			else if($this->params['field'] == "groups_id"){
				$where .= " AND groups_os.groups_id =".$this->params['value'];
			}
		}
		
		$res = $this->tool->query($sql.$left_join." WHERE ".$where." ORDER BY os.name");
		$rows = $res->fetchAll();
		return json_encode($rows);
	}
}

?>