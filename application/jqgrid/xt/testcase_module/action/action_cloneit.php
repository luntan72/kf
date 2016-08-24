<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_cloneit.php');

class xt_testcase_module_action_cloneit extends action_cloneit{
	// protected function afterSave($affectedID){
		// $sql = "INSERT INTO prj_testcase_ver (prj_id, testcase_id, testcase_ver_id) ".
			// " SELECT $affectedID, testcase_id, testcase_ver_id FROM prj_testcase_ver WHERE prj_id=$orig_id";
// //print_r($sql);		
		// $this->db->query($sql);
	// }
}
?>