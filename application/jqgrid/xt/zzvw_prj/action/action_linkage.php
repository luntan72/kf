<?php
require_once('action_jqgrid.php');

class xt_zzvw_prj_action_linkage extends action_jqgrid{

	protected function handlePost(){
		$sql = "SELECT DISTINCT prj.id, prj.name FROM prj LEFT JOIN os_testcase_type ON os_testcase_type.os_id = prj.os_id
			LEFT JOIN group_testcase_type ON group_testcase_type.testcase_type_id = os_testcase_type.testcase_type_id
			WHERE group_testcase_type.group_id IN (".$this->userInfo->group_ids.")";
		if(!empty($this->params['os_id']))
			$sql .= " AND prj.os_id=".$this->params['os_id'];
		if(!empty($this->params['chip_id']))
			$sql .= " AND prj.chip_id=".$this->params['chip_id'];
		if(!empty($this->params['board_type_id']))
			$sql .= " AND prj.board_type_id=".$this->params['board_type_id'];
		$sql .= " ORDER BY prj.name";
// print_r($sql);
		$res = $this->db->query($sql);
		$rows = $res->fetchAll();
		return json_encode($rows);
	}
	
	
}

?>