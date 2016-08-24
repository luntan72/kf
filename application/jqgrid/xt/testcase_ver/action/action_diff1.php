<?php
require_once('action_jqgrid.php');

class xt_testcase_ver_action_diff extends action_jqgrid{
	protected function handleGet(){
		$ids = implode(',', json_decode($this->params['vers']));
		$rows = array();
		$sql = "SELECT testcase.code, testcase.summary, testcase_ver.*, edit_status.name as edit_status, GROUP_CONCAT(prj.name) as project ".
			" FROM testcase_ver left join testcase on testcase_ver.testcase_id=testcase.id".
			" left join prj_testcase_ver on prj_testcase_ver.testcase_ver_id=testcase_ver.id".
			" left join prj on prj_testcase_ver.prj_id=prj.id".
			" left join edit_status on testcase_ver.edit_status_id=edit_status.id".
			" WHERE testcase_ver.id IN ($ids)";
		$res = $this->db->query($sql);
		while($row = $res->fetch())
			$rows[' [version '.$row['ver']."]"] = $row;
		$this->renderView("testcase_ver_diff.phtml", array('vers'=>$rows));
	}
}

?>