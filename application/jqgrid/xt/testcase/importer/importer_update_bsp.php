<?php
require_once('importer_base.php');

class xt_testcase_importer_update_bsp extends importer_base{
	protected function _import($fileName){
		// $this->parse($fileName);
		return $this->process();
	}
	
	protected function process(){
		$done = True;
		if(!empty($this->params['prj_ids']))
			$this->params['prj_ids'] = implode(",", $this->params['prj_ids']);
		
		switch($this->params['testcase_type_id']){
			case TESTCASE_TYPE_CODEC :
			case TESTCASE_TYPE_LINUX_BSP :
				$res = $this->tool->query("SELECT testcase.id, code FROM testcase LEFT JOIN testcase_module ON testcase_module.id = testcase.testcase_module_id".
					" WHERE testcase.code LIKE 'TGE_%' AND testcase_type_id=".TESTCASE_TYPE_LINUX_BSP." AND testcase_module.name LIKE 'GPU_%_L'");
				while($row = $res->fetch()){
print_r($row["code"]."\n<br>");
					$row["code"] = str_ireplace("_", "-", $row["code"]);
					$this->tool->update("testcase", array("code"=>$row["code"]), "id=".$row["id"]);
					$ver_res = $this->tool->query("SELECT id, associated_code FROM testcase_ver WHERE testcase_id={$row["id"]}");
					while($info = $ver_res->fetch()){
						$this->tool->update("testcase_ver", array("associated_code"=>$row["code"]), "id=".$info["id"]);
					}
				}
				break;
			case TESTCASE_TYPE_KSDK_DEMO:
			case TESTCASE_TYPE_KSDK_EXAMPLE:
			case TESTCASE_TYPE_KSDK_RTOS:
				break;
			default:
				$done = False;
				print_r("This case type does not support now! Please contact with XiaoTian Administrator");
				break;
		}
		if($done){
			print_r("Process Done!!! <br />");
			print_r("Update Successfully!!! <br />");
		}
		else{
			print_r("This case type does not support now! Please contact with XiaoTian Administrator");
		}
		
	}	
}
?>
