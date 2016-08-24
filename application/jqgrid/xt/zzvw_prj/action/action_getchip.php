<?php
require_once('action_jqgrid.php');
/*
保存前应先检查是否已经有自己创建的处于非published状态的且关联的project一致的Version存在，如果已经存在，则应询问是否覆盖到该Version

*/
class xt_zzvw_prj_action_getchip extends action_jqgrid{
	protected function handlePost(){
		$params = $this->params;
		$sql = "SELECT DISTINCT chip.id, chip.name FROM prj LEFT JOIN chip ON prj.chip_id=chip.id
			LEFT JOIN os_testcase_type ON os_testcase_type.os_id = prj.os_id
			LEFT JOIN group_testcase_type ON group_testcase_type.testcase_type_id = os_testcase_type.testcase_type_id";
		$where = 'chip.name is not null AND group_testcase_type.group_id IN ('.$this->userInfo->group_ids.')';
		if (!empty($params['os_id']))
			$where .= " AND prj.os_id=".$params['os_id'];
		if (!empty($params['board_type_id']))
			$where .= " AND prj.board_type_id=".$params['board_type_id'];
		$sql .= " WHERE $where ORDER BY name ASC";
		$res = $this->db->query($sql);
		return json_encode($res->fetchAll());		
	}
}
?>