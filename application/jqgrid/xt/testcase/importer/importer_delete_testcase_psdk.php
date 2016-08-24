<?php
require_once('importer_base.php');

class xt_testcase_importer_delete_testcase_psdk extends importer_base{
	
	protected function _import($fileName){
		// $this->parse($fileName);
		return $this->process();
	}
	
	protected function process(){
		//psdk: 9, 11, 12, 13
		$this->tool->delete("testcase_type", "id in (9, 11, 12, 13)");
		$this->tool->delete("testcase_module_testcase_type", "testcase_type_id in (9, 11, 12, 13)");
		$this->tool->delete("os_testcase_type", "testcase_type_id in (9, 11, 12, 13)");
		$this->tool->delete("group_testcase_type", "testcase_type_id in (9, 11, 12, 13)");
		$res = $this->tool->query("select * from testcase where testcase_type_id in (9, 11, 12, 13)");
		while($row = $res->fetch()){
print_r($row['code']."\n<br />");
			$this->tool->delete("testcase", "id=".$row['id']);
			$this->tool->delete("testcase_module", "id=".$row['testcase_module_id']);
			$this->tool->delete("testcase_testpoint", "id=".$row['testcase_testpoint_id']);
			$this->tool->delete("testcase_testpoint", "testcase_module_id=".$row['testcase_module_id']);
			$this->tool->delete("testcase_ver", "testcase_id=".$row['id']);
			$this->tool->delete("prj_testcase_ver", "testcase_id=".$row['id']);
			$this->tool->delete("prj_testcase_ver_history", "testcase_id=".$row['id']);
			$this->tool->delete("testcase_last_result", "testcase_id=".$row['id']);
		}
	}

}
?>
