<?php
require_once('action_jqgrid.php');

class xt_testcase_type_action_linkage extends action_jqgrid{
	protected function handlePost(){
		$sql = "SELECT distinct testcase_type.id, testcase_type.name FROM testcase_type 
			left join os_testcase_type on os_testcase_type.testcase_type_id = testcase_type.id
			left join group_testcase_type on group_testcase_type.testcase_type_id = os_testcase_type.testcase_type_id";
		$where = "group_testcase_type.group_id in (".$this->userInfo->group_ids.")";
		if(!empty($this->params['os_id'])){
			$where .= " AND os_testcase_type.os_id = ".$this->params['value'];
		}
		$res = $this->tool->query($sql." WHERE ".$where." ORDER BY testcase_type.name");
		$rows = $res->fetchAll();
		return json_encode($rows);
	}
}

?>