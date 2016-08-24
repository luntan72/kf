<?php
require_once('importer_excel.php');

class xt_testcase_importer_update extends importer_excel{
	protected function processSheetData($title, $sheet_data){
		$done = True;
		if(!empty($this->params['prj_ids']))
			$this->params['prj_ids'] = implode(",", $this->params['prj_ids']);
		
		foreach($sheet_data as $data){
			$testcase_data = array();
			$ver_data = array();
			switch($this->params['testcase_type_id']){
				case TESTCASE_TYPE_CODEC :
				case TESTCASE_TYPE_LINUX_BSP :
					//need check
					continue;
					if(empty($data['code']))
						continue;
					$res = $this->tool->query("SELECT id FROM testcase WHERE code = '".$data['code']."' AND testcase_type_id=".TESTCASE_TYPE_LINUX_BSP);
					if($row = $res->fetch()){
						// update code
						if( !empty( $data['code'] ) ){
							// if same, no need to update
							if(trim($row['code']) != trim($data['code'])){
								$testcase_data['code'] = trim($data['code']);
								$ver_data['code'] = trim($data['code']);
							}
						}
						// update summary
						if( !empty( $data['summary'] ) ){
							// if same, no need to update
							if(trim($row['summary']) != trim($data['summary'])){
								$testcase_data['summary'] = trim($data['summary']);
								$ver_data['summary'] = trim($data['summary']);
							}
						}					
						// update module
						if(!empty($data['testcase_module'])){
							// get module id
							$testcase_module_id = $this->tool->getElementId("testcase_module", array("name"=>$data['testcase_module'], 
								"testcase_type_id"=>$this->params['testcase_type_id'], "creater_id"=>$this->params['owner_id'], 
								"owner_id"=>$this->params['owner_id']), array("name"));
								
							// if same, no need to update 
							// update relative testpoint
							if( $testcase_module_id && ($row['testcase_module_id'] != $testcase_module_id)){
								$testcase_data['testcase_module_id'] = $testcase_module_id;
								if(empty($data['testcase_testpoint'])){
									$data['testcase_testpoint'] = "Default testpoint for ".$data['testcase_module'];
								}
								$testcase_data['testcase_testpoint_id'] = $this->tool->getElementId("testcase_testpoint", 
									array("name"=>$data['testcase_testpoint'], "testcase_module_id"=>$testcase_module_id), array("name"));
							}
						}
						// update testcase info
						if(!empty($testcase_data))
							$this->tool->update("testcase", $testcase_data, "id=".$row['id']);
						
						// update resource link
						if(!empty($data['resource_link']))
							$ver_data['resource_link'] = $data['resource_link'];
						if(!empty($data['configure']))
							$ver_data['configure'] = $data['configure'];						
						// update testcase version info
						if(!empty($ver_data)){
							$sql_new = "SELECT DISTINCT ptv.testcase_ver_id, ver.update_comment FROM prj_testcase_ver ptv".
								" LEFT JOIN testcase_ver ver ON ver.id=ptv.testcase_ver_id".
								" WHERE ptv.testcase_id = {$row['id']}".
								" AND ver.id IS NOT NULL".
								" AND ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")";
							// if prj in file, then use it; if not, use user input; if not, update all versions
							if (!empty($this->params['prj'])){
								$prj_id = $this->tool->getElementId("prj", array('name'=>$this->params['prj']), array("name"));
								$sql_new .= " AND ptv.prj_id = ".$prj_id;
							}
							else if(!empty($this->params['prj_ids'])){
								$sql_new .= " AND ptv.prj_id in ({$this->params['prj_ids']})";
							}
							$res_new = $this->tool->query($sql_new);
							while( $info = $res_new->fetch() ){
								$update = array();
								//if same, no need to update
								if($info['resource_link'] != $ver_data['resource_link'])
									$update['resource_link'] = $ver_data['resource_link'];
								if($info['configure'] != $ver_data['configure'])
									$update['configure'] = $ver_data['configure'];
								//update new value for these fields
								$update['update_comment'] = "[ Document Update AT ".date("Y-m-d h:i:s")." ]"."\n".$info['update_comment'];
								$this->tool->update("testcase_ver", $update, "id=".$info['testcase_ver_id']);
							}
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
