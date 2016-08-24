<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/testcase/importer/importer_testcase.php');

class xt_testcase_importer_testcase_psdk extends xt_testcase_importer_testcase{
	
	protected function processPrjCaseVer($caseInfo, $verInfo){
		$prj_ids = array();
		$res = $this->tool->query("SELECT id as testcase_ver_id, testcase_id, owner_id, testcase_priority_id, edit_status_id, auto_level_id".
			" FROM testcase_ver WHERE id={$verInfo['testcase_ver_id']}");
		$ver = $res->fetch();
// print_r($caseInfo);
		if (!isset($this->params['prj_ids'])){
			if (isset($caseInfo['platform']) && isset($caseInfo['os'])){
				$platforms = explode(';', $caseInfo['platform']);
				$os = $caseInfo['os'];
				foreach($platforms as $platform){
					if (empty($platform))
						continue;
					//需要规范platform和board_type，连接符用-
					$prj_id = $this->getProject($platform, $os, $isNew);
					$prj_ids[$prj_id] = $prj_id;
				}
			}
			else if(!empty($caseInfo['project'])){
				$projects = explode(",", $caseInfo['project']);
				foreach($projects as $project){
					if(preg_match("/^(.*?)-(.*)-(.*)$/i", trim($project), $matches)){
						$chip = trim($matches[1]);
						$board_type = trim($matches[2]);
						$os = trim($matches[3]);
						$board_type_id = $this->getId("board_type", array("name"=>$board_type), array("name"));
						if($board_type_id == "error")
							continue;
						$os_id = $this->getId("os", array("name"=>$os), array("name"));
						if($os_id == "error")
							continue;
						// $chip_id = $this->getChipId($chip, $os_id, $board_type_id);
						$chip_id = $this->tool->getChipId($chip, array("os_id"=>$os_id, "board_type_id"=>$board_type_id));
						if($chip_id == "error")
							continue;
						
						$prj = array("name"=>trim($project), "chip_id"=>$chip_id, "board_type_id"=>$board_type_id, "os_id"=>$os_id );
						$prj_id = $this->tool->getElementId("prj", $prj, array("name", "chip_id", "board_type_id","os_id"));	
						$prj_ids[] = $prj_id;
					}
				}
			}
		}
		else{
			$prj_ids = $this->params['prj_ids'];
		}		
		if(!empty($prj_ids)){
			foreach($prj_ids as $prj_id){
				if($prj_id == 'xxx')
					continue;
				$link = $ver;
				$link['prj_id'] = $prj_id;
				$this->updatePrjCaseVer($link);
			}
		}
	}
}
?>
