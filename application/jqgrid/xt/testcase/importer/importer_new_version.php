<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/testcase/importer/importer_testcase.php');

class xt_testcase_importer_new_version extends xt_testcase_importer_testcase{

	protected function processPrjCaseVer($caseInfo, $verInfo){
		$prj_ids = array();
		$res = $this->tool->query("SELECT id as testcase_ver_id, testcase_id, owner_id, testcase_priority_id, edit_status_id, auto_level_id".
			" FROM testcase_ver WHERE id={$verInfo['testcase_ver_id']}");
		$ver = $res->fetch();
// print_r($caseInfo);
		if (!isset($caseInfo['prj_ids'])){
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
			else{
				if(isset($this->params['prj_ids']) && $this->params['prj_ids'])
					$prj_ids = $this->params['prj_ids'];
				else if(!empty($caseInfo['project'])){
					$projects = explode(",", $caseInfo['project']);	
					foreach($projects as $project){
						$prj_id = $this->getId('prj', array('name'=>$project), array('name'));
						if('error' != $prj_id)
							$prj_ids[] = $prj_id;
					}
				}
			}
		}
		else{
			$prj_ids = $caseInfo['prj_ids'];
		}		
		if(!empty($prj_ids)){
			foreach($prj_ids as $prj_id){
				if($prj_id == 'xxx')
					continue;
				$link = $ver;
				$link['prj_id'] = $prj_id;
				$history = array('prj_id'=>$prj_id, 'testcase_id'=>$ver['testcase_id'], 'act'=>'remove');
				$res0 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
					" left join testcase_ver on testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
					" WHERE prj_testcase_ver.prj_id=$prj_id".
					" AND prj_testcase_ver.testcase_id={$ver['testcase_id']} AND prj_testcase_ver.testcase_ver_id={$ver['testcase_ver_id']}".
					" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")");
				if($row0 = $res0->fetch()){
					continue;
				}
				else{
					$res1 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
						" left join testcase_ver on testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
						" WHERE prj_testcase_ver.prj_id=$prj_id".
						" AND prj_testcase_ver.testcase_id={$ver['testcase_id']}".
						" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")");
					while($row1 = $res1->fetch()){
						$this->tool->delete('prj_testcase_ver', "prj_id=$prj_id AND testcase_id={$ver['testcase_id']}");
						$history['testcase_ver_id'] = $row1['testcase_ver_id'];
						$history['act'] = 'delete';
						$this->tool->insert('prj_testcase_ver_history', $history);
					}
					$history['testcase_ver_id'] = $ver['testcase_ver_id'];
					$history['act'] = 'add';
					$this->tool->insert('prj_testcase_ver', $link);
					$this->tool->insert('prj_testcase_ver_history', $history);
				}
			}
		}
		// }
	}

}
?>
