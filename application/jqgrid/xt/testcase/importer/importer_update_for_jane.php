<?php
require_once('importer_excel.php');

class xt_testcase_importer_update_for_jane extends importer_excel{
	protected function processSheetData($title, $sheet_data){
		$func = "process_".$title;
		if(method_exists($this, $func))
			$ret = $this->$func($sheet_data);
	}	
	
	private function process_auto_new($sheet_data){
		print_r("auto_new~~~~~~");
		foreach($sheet_data as $data){
			if(!empty($data['code']) && !empty($data["project"])){
				if(empty($data['auto_level']))
					continue;
				$auto_level_id = $this->tool->getExistedId("auto_level", array("name"=>$data['auto_level']));
				if($auto_level_id == "error")
					continue;
print_r("123");
				$prj_id = $this->tool->getExistedId("prj", array("name"=>$data['project']));
				if($prj_id == "error")
					continue;
print_r("456");
				$sql = "SELECT ver.* FROM testcase_ver ver LEFT JOIN testcase ON testcase.id = ver.testcase_id".
					" LEFT JOIN prj_testcase_ver ptv ON ptv.testcase_ver_id = ver.id".
					" WHERE testcase.code = '{$data['code']}' AND ptv.prj_id = {$prj_id}".
					" AND ver.edit_status_id in (1,2) AND ptv.id IS NOT NULL";
print_r("123");
				$res = $this->tool->query($sql);
print_r("456");
				if($ver_info = $res->fetch()){
					$ver_info["auto_level_id"] = $auto_level_id;
					$ver_info["config"] = $data['config'];
					$ver_info["updater_id"] = $this->params["owner_id"];
					$ver_info["updated"] = date('Y-m-d H:i:s');
					$ver_info["from"] = $ver_info["ver"];
					$new_sql = "SELECT count(*) as ver FROM testcase_ver WHERE testcase_id={$ver_info["testcase_id"]}";
					$new_res = $this->tool->query($new_sql);
					if($new_ver_info = $new_res->fetch())
						$ver_info["ver"] = $new_ver_info["ver"] + 1;
					else
						continue;
					unset($ver_info["id"]);
					$ver_info["update_comment"] = $ver_info["update_comment"]."\n"."[Jinfeng Liu At ".$ver_info["updated"]."]".$data['update_comment'];
					$testcase_ver_id = $this->tool->getElementId("testcase_ver", $ver_info);
					$link = array("prj_id"=>$prj_id, "testcase_ver_id"=>$testcase_ver_id, "testcase_id"=>$ver_info["testcase_id"]);
					$this->updatePrjCaseVer($link);
				}
			}
		}
	}
	
	protected function updatePrjCaseVer($link){
		$history = array('prj_id'=>$link['prj_id'], 'testcase_id'=>$link['testcase_id'], 'act'=>'remove');
		$res0 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
			" left join testcase_ver on testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
			" WHERE prj_testcase_ver.prj_id={$link['prj_id']}".
			" AND prj_testcase_ver.testcase_id={$link['testcase_id']} AND prj_testcase_ver.testcase_ver_id={$link['testcase_ver_id']}".
			" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")");
		if($row0 = $res0->fetch()){
			return;
		}
		else{
			$res1 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
				" left join testcase_ver on testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
				" WHERE prj_testcase_ver.prj_id={$link['prj_id']}".
				" AND prj_testcase_ver.testcase_id={$link['testcase_id']}".
				" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")");
			while($row1 = $res1->fetch()){
				$this->tool->delete('prj_testcase_ver', "prj_id={$link['prj_id']} AND testcase_id={$link['testcase_id']}");
				$history['testcase_ver_id'] = $row1['testcase_ver_id'];
				$history['act'] = 'remove';
				$this->tool->insert('prj_testcase_ver_history', $history);
			}
			$history['testcase_ver_id'] = $link['testcase_ver_id'];
			$history['act'] = 'add';
			$this->tool->insert('prj_testcase_ver', $link);
			$this->tool->insert('prj_testcase_ver_history', $history);
		}
	}
	
	private function process_manual_no_new($sheet_data){
		print_r("manual_no_new~~~~~~");
		foreach($sheet_data as $data){
			if(!empty($data['code']) && !empty($data["project"])){
				$prj_id = $this->tool->getExistedId("prj", array("name"=>$data['project']));
				if($prj_id == "error")
					continue;
				$sql = "SELECT ver.* FROM testcase_ver ver LEFT JOIN testcase ON testcase.id = ver.testcase_id".
					" LEFT JOIN prj_testcase_ver ptv ON ptv.testcase_ver_id = ver.id".
					" WHERE testcase.code = '{$data['code']}' AND ptv.prj_id = {$prj_id}".
					" AND ver.edit_status_id in (1,2) AND ptv.id IS NOT NULL";
				$res = $this->tool->query($sql);
				if($ver_info = $res->fetch()){
					if(!empty($data['precondition'])){
						if(!empty($ver_info["precondition"]))
							$ver_info["precondition"] = $ver_info["precondition"]."\n".$data['precondition'];
						else
							$ver_info["precondition"] = $data['precondition'];
					}
					$ver_info["config"] = $data['config'];
					$ver_info["updater_id"] = $this->params["owner_id"];
					$ver_info["updated"] = date('Y-m-d H:i:s');
					$ver_info["update_comment"] = $ver_info["update_comment"]."\n"."[Jinfeng Liu At ".$ver_info["updated"]."]".$data['update_comment'];
					$this->tool->update("testcase_ver", 
						array("precondition"=>$ver_info["precondition"], "update_comment"=>$ver_info['update_comment'], 
							"updated"=>$ver_info["updated"], "config"=>$ver_info["config"] , "updater_id"=>$ver_info["updater_id"]),
							"id=".$ver_info["id"]);
					// $this->tool->update("testcase_ver", 
						// array("updated"=>$ver_info["updated"] , "updater_id"=>$ver_info["updater_id"], "update_comment"=>$ver_info['update_comment']),"id=".$ver_info["id"]);
				}
			}
		}
	}
	
	// private function process_manual_no_new($sheet_data){
		// print_r("manual_no_new~~~~~~");
		// foreach($sheet_data as $data){
			// if(!empty($data['code']) && !empty($data["project"])){
				// $prj_id = $this->tool->getExistedId("prj", array("name"=>$data['project']));
				// if($prj_id == "error")
					// continue;
				// $sql = "SELECT ver.* FROM testcase_ver ver LEFT JOIN testcase ON testcase.id = ver.testcase_id".
					// " LEFT JOIN prj_testcase_ver ptv ON ptv.testcase_ver_id = ver.id".
					// " WHERE testcase.code = '{$data['code']}' AND ptv.prj_id = {$prj_id}".
					// " AND ver.edit_status_id in (1,2) AND ptv.id IS NOT NULL";
				// $res = $this->tool->query($sql);
				// if($ver_info = $res->fetch()){
					// if(!empty($data['precondition'])){
						// if(!empty($ver_info["precondition"]))
							// $ver_info["precondition"] = $ver_info["precondition"]."\n".$data['precondition'];
						// else
							// $ver_info["precondition"] = $data['precondition'];
					// }
					// $ver_info["config"] = "";
					// $ver_info["updater_id"] = $this->params["owner_id"];
					// $ver_info["updated"] = date('Y-m-d H:i:s');
					// $ver_info["update_comment"] = $ver_info["update_comment"]."\n"."[Jinfeng Liu At ".$ver_info["updated"]."]".$data['update_comment'];
					// $this->tool->update("testcase_ver", 
						// array("precondition"=>$ver_info["precondition"], "update_comment"=>$ver_info['update_comment'], 
							// "updated"=>$ver_info["updated"], "config"=>$ver_info["config"] , "updater_id"=>$ver_info["updater_id"]),
							// "id=".$ver_info["id"]);
					// // $this->tool->update("testcase_ver", 
						// // array("updated"=>$ver_info["updated"] , "updater_id"=>$ver_info["updater_id"], "update_comment"=>$ver_info['update_comment']),"id=".$ver_info["id"]);
				// }
			// }
		// }
	// }
	
	private function process_auto_no_new($sheet_data){
		print_r("auto_no_new~~~~~~");
		foreach($sheet_data as $data){
			if(!empty($data['code']) && !empty($data["project"])){
				$prj_id = $this->tool->getExistedId("prj", array("name"=>$data['project']));
				if($prj_id == "error")
					continue;
				$sql = "SELECT ver.* FROM testcase_ver ver LEFT JOIN testcase ON testcase.id = ver.testcase_id".
					" LEFT JOIN prj_testcase_ver ptv ON ptv.testcase_ver_id = ver.id".
					" WHERE testcase.code = '{$data['code']}' AND ptv.prj_id = {$prj_id}".
					" AND ver.edit_status_id in (1,2) AND ptv.id IS NOT NULL";
				$res = $this->tool->query($sql);
				if($ver_info = $res->fetch()){
					if(!empty($data['precondition'])){
						if(!empty($ver_info["precondition"]))
							$ver_info["precondition"] = $ver_info["precondition"]."\n".$data['precondition'];
						else
							$ver_info["precondition"] = $data['precondition'];
					}
					$ver_info["updater_id"] = $this->params["owner_id"];
					$ver_info["updated"] = date('Y-m-d H:i:s');
					$ver_info["update_comment"] = $ver_info["update_comment"]."\n"."[Jinfeng Liu At ".$ver_info["updated"]."]".$data['update_comment'];
					$this->tool->update("testcase_ver", 
						array("precondition"=>$ver_info["precondition"], "update_comment"=>$ver_info['update_comment'], 
							"updated"=>$ver_info["updated"], "updater_id"=>$ver_info["updater_id"]),
							"id=".$ver_info["id"]);
					// $this->tool->update("testcase_ver", 
						// array("updated"=>$ver_info["updated"] , "updater_id"=>$ver_info["updater_id"]),"id=".$ver_info["id"]);
				}
			}
		}
	}
}
?>
