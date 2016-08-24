<?php
require_once('action_jqgrid.php');
class xt_zzvw_prj_action_getos extends action_jqgrid{
	protected function handlePost(){
		$params = $this->params;
		$sql = "SELECT DISTINCT os.id, os.name FROM prj LEFT JOIN os ON prj.os_id=os.id
			LEFT JOIN os_testcase_type ON os_testcase_type.os_id = prj.os_id
			LEFT JOIN group_testcase_type ON group_testcase_type.testcase_type_id = os_testcase_type.testcase_type_id";
		$where = 'os.name is not null AND group_testcase_type.group_id IN ('.$this->userInfo->group_ids.')';
		if (!empty($params['chip_id']))
			$where .= " AND prj.chip_id=".$params['chip_id'];
		if (!empty($params['board_type_id']))
			$where .= " AND prj.board_type_id=".$params['board_type_id'];
		$sql .= " WHERE $where ORDER BY name ASC";
		$res = $this->db->query($sql);
		return json_encode($res->fetchAll());
	}
}
?>