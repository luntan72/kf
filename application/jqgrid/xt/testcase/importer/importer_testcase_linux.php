<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/testcase/importer/importer_testcase.php');

class xt_testcase_importer_testcase_linux extends xt_testcase_importer_testcase{
	
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
					$caseInfo['project'] = explode(',', $caseInfo['project']);
					foreach($caseInfo['project'] as $project){
						$res = $this->tool->query("select id, name from prj where name = '".trim($project)."'");
						if($row = $res->fetch()){
							$prj_ids[] = $row['id'];
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
