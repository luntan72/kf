<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_cloneit.php');

class xt_zzvw_prj_action_cloneit extends action_cloneit{
	protected function _saveOne($db, $table, $pair){
		$prj_id = parent::_saveOne($db, 'prj', $pair);
		$sql = "INSERT INTO prj_testcase_ver (prj_id, testcase_id, testcase_ver_id, note, owner_id, testcase_priority_id, edit_status_id, auto_level_id) ".
			" SELECT $prj_id, ptv.testcase_id, ptv.testcase_ver_id, ptv.note, ptv.owner_id, ver.testcase_priority_id, ver.edit_status_id, ver.auto_level_id".
			" FROM prj_testcase_ver ptv LEFT JOIN testcase_ver ver ON ver.id = ptv.testcase_ver_id".
			" WHERE ptv.prj_id={$this->orig_id} AND (ver.edit_status_id=".EDIT_STATUS_PUBLISHED." OR ver.edit_status_id=".EDIT_STATUS_GOLDEN.")";
//print_r($sql);		
		$this->db->query($sql);
		return $prj_id;
	}
}
?>