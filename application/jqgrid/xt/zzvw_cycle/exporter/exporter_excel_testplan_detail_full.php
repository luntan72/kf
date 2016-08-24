<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/exporter/exporter_excel_testplan_detail.php');

class xt_zzvw_cycle_exporter_excel_testplan_detail_full extends xt_zzvw_cycle_exporter_excel_testplan_detail{
	
	protected function getData($table_desc, $searchConditions = array(), $order = array()){
		$i = 0;
		$data = array();
		$db = dbFactory::get($this->params['db']);
		$useradmin = dbFactory::get("useradmin");
		$ids = implode(',', $this->params['id']);
		
		if(empty($this->params['prj_id']))
			$this->params['prj_id']	= $this->params['prj_ids'];
		elseif(!is_array($this->params['prj_id']))
			$this->params['prj_id'] = array($this->params['prj_id']);
			
		$prj_lists = implode(",", $this->params['prj_id']);
		// $sql = "select group_concat(distinct testcase_id) from cycle_detail where cycle_id in ($ids) order by testcase_id asc";
		$res = $db->query("select distinct ptv.testcase_id from prj_testcase_ver ptv".
			" left join testcase on ptv.testcase_id = testcase.id".
			" left join testcase_ver ver on ptv.testcase_ver_id = ver.id".
			" where ptv.prj_id in (".$prj_lists.") and testcase.testcase_type_id = ".TESTCASE_TYPE_LINUX_BSP.//" where prj.os_id = {$this->params['os_id']} and testcase.testcase_type_id = 1".
			" and ver.testcase_priority_id in (1,2,3) and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")".
			" and testcase.isactive = ".ISACTIVE_ACTIVE);
		while($row = $res->fetch()){
			$sql = "select testcase.id as testcase_id, testcase.code as code, testcase.summary as summary, testcase_type.name as type,".
				" testcase_source.name as source, testcase_category.name as category, testcase_module.name as module,".
				" testcase_testpoint.name as testpoint, group_concat(distinct testcase_priority.name) as testcase_priority,".
				" group_concat(distinct auto_level.name) as testcase_auto_level from testcase".
				" left join testcase_type on testcase_type.id = testcase.testcase_type_id".
				" left join testcase_source on testcase_source.id = testcase.testcase_source_id".
				" left join testcase_category on testcase_category.id = testcase.testcase_category_id".
				" left join testcase_module on testcase_module.id = testcase.testcase_module_id".
				" left join testcase_testpoint on testcase_testpoint.id = testcase.testcase_testpoint_id".
				" left join prj_testcase_ver ptv on ptv.testcase_id=testcase.id".
				" left join testcase_ver ver on ptv.testcase_ver_id = ver.id".
				" left join testcase_priority on testcase_priority.id = ver.testcase_priority_id".
				" left join auto_level on auto_level.id = ver.auto_level_id".
				" where testcase.id={$row['testcase_id']} and ptv.prj_id in (".$prj_lists.")".//" where prj.os_id = {$this->params['os_id']} and testcase.testcase_type_id = 1".
				" and ver.testcase_priority_id in (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.
				") and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.") order by testcase.id asc";
			$result = $db->query($sql);
			
// print_r($this->params['prj_ids']);
			if($info = $result->fetch()){
				$data[$i] = $info;
				$data[$i]['id'] = $info['testcase_id'];
				unset($data[$i]['testcase_id']);
				$data[$i]['priority'] = "";
				$data[$i]['auto_level'] = "";
				foreach($this->params['prj_ids'] as $prj_id){
					$prj_name = $this->params['prjs'][$prj_id];
					$data[$i][$prj_name] = "";			
				}
				$data[$i]['commiter'] = "";
				$sql0 = "select detail.prj_id, detail.tester_id, testcase_priority.name as priority, auto_level.name as auto_level, result_type.name as result_type".
					" from cycle_detail detail".
					" left join testcase_ver ver on ver.id = detail.testcase_ver_id".
					" left join testcase_priority on testcase_priority.id = ver.testcase_priority_id".
					" left join auto_level on auto_level.id = ver.auto_level_id".
					" left join result_type on result_type.id = detail.result_type_id".
					" where detail.testcase_id = {$info['testcase_id']} and detail.prj_id in (".implode(",", $this->params['prj_ids']).")".
					" and detail.cycle_id in ($ids) order by detail.finish_time";
				$res0 = $db->query($sql0);
				while($detail=$res0->fetch()){
					$prj = $this->params['prjs'][$detail['prj_id']];
					if(stripos($data[$i]['priority'], ",") !== false){
						if($data[$i]['priority'] != $detail['priority'])
							$data[$i]['priority'] = $data[$i]['priority'].", ".$detail['priority'];
					}
					else
						$data[$i]['priority'] = $detail['priority'];
						
					if(stripos($data[$i]['auto_level'], ",") !== false){
						if($data[$i]['auto_level'] != $detail['auto_level'])
							$data[$i]['auto_level'] = $data[$i]['auto_level'].", ".$detail['auto_level'];
					}
					else
						$data[$i]['auto_level'] = $detail['auto_level'];
						
					if(!empty($detail['tester_id'])){
						$usrRes = $useradmin->query("select username, nickname from users where id = {$detail['tester_id']}");
						if($user = $usrRes->fetch()){
							if(stripos($data[$i]['commiter'], ",") !== false){
								if($data[$i]['commiter'] != $user['nickname'])
									$data[$i]['commiter'] = $data[$i]['commiter'].", ".$user['nickname'];
							}
							else
								$data[$i]['commiter'] = $user['nickname'];
						}
					}	
					$data[$i][$prj] = $this->getInfoForCase($detail['result_type']);
				}
				if(empty($data[$i]['priority']))
					$data[$i]['priority'] = $data[$i]['testcase_priority'];
				unset($data[$i]['testcase_priority']);
				if(empty($data[$i]['auto_level']))
					$data[$i]['auto_level'] = $data[$i]['testcase_auto_level'];
				unset($data[$i]['testcase_auto_level']);
				
				$i ++;	
			}
			// if(count($this->params['prj_ids']) == $ns)
				// unset($data[$i]);
			
		}
		return $data;
	}
};

?>
