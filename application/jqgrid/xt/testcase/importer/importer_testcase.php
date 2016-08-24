<?php
require_once('importer_excel.php');

class xt_testcase_importer_testcase extends importer_excel{
	protected $userAdmin = null;
	protected $userInfo = null;
	protected $tag = array();
	protected function init($params){
		parent::init($params);
		if (empty($this->params['testcase_type_id']))
			$this->params['testcase_type_id'] = TESTCASE_TYPE_LINUX_BSP; //LINUX BSP
		$this->userAdmin = new Application_Model_Useradmin(null);
		$this->userInfo = $this->userAdmin->getUserInfo();
		if ($this->params['config_file'] == APPLICATION_PATH.'/jqgrid/xt/testcase/android_kk.karen.config.php'){
			$this->tag = array('name'=>'ANDROID_KK_CORE', 'table'=>'xt.testcase', 'public'=>1, 'element_id'=>array());
		}
	}
	
	protected function processSheetData($title, $sheet_data){
		foreach($sheet_data as $case){
			if (empty($case['code']))
				continue;
			if(empty($case['testcase_module']))
				$case['testcase_module'] = $title;
			if(empty($case['testcase_testpoint']) || $this->params['config_file'] == APPLICATION_PATH.'/jqgrid/xt/testcase/config/android_kk.karen.config.php'){
				$case['testcase_testpoint'] = 'Default Testpoint For '.$case['testcase_module'];
			}
			// if ($this->params['config_file'] == APPLICATION_PATH.'/jqgrid/xt/testcase/config/psdk.bill.config.php'){
				// $case['testcase_type'] = $title;
			// }
			$verInfo = $this->processCase($case);
			if(!empty($verInfo))
				$this->processPrjCaseVer($case, $verInfo);
			
		}
		if (!empty($this->tag['element_id'])){
			$this->tag['element_id'] = implode(',', $this->tag['element_id']);
			$this->tool->getElementId('tag', $this->tag, array('name', 'table'));
		}
		// print_r("\n<BR /> Finished to upload the sheet: $title");
	}

	protected function processCase($case){
		if(!empty($case['auto_level'])){
			if(strtolower($case['auto_level']) == 'semi-auto')
				$case['auto_level'] = 'Partial Auto';
			else if(strtolower($case['auto_level']) == 'cancel'){
				unset($case['auto_level']);
				$case['isactive'] = ISACTIVE_INACTIVE;
			}
		}
		if(!empty($case['testcase_priority']) && strlen($case['testcase_priority']) == 1)
			$case['testcase_priority'] = 'P'.$case['testcase_priority'];
			
		if (!empty($case['owner'])){
			$table = $this->userAdmin->getUserTable();
			$res = $this->tool->query("SELECT id FROM $table WHERE nickname='".$case['owner']."'");
			$row = $res->fetch();
			$case['owner_id'] = $row['id'];
		}
		else
			$case['owner_id'] = $this->params['owner_id'];
		
		$case['updater_id'] = $this->userInfo->id;
		
		$transfer_fields = array('testcase_type', 'testcase_module', 'testcase_testpoint', 'testcase_category'=>'Function', 'testcase_source'=>'FSL MAD', 
			'auto_level'=>'MANUAL', 'testcase_priority'=>'P3');
		$fields_value = $this->tool->extractItems($transfer_fields, $case);

		if (empty($fields_value['testcase_type']))
			$case['testcase_type_id'] = $this->params['testcase_type_id'];
			
		foreach($fields_value as $field=>$value){
			if (empty($case[$field.'_id'])){
				$case[$field.'_id'] = 0;
				if(!empty($value) || $value != ''){
					$valuePair = array('name'=>$value);
					if ($field == 'testcase_testpoint'){
						$valuePair['testcase_module_id'] = $case['testcase_module_id'];
					}
					else if ($field == 'testcase_module'){
						$valuePair['testcase_type_ids'] = $case['testcase_type_id'];
					}
					$case[$field.'_id'] = $this->tool->getElementId($field, $valuePair, array('name'), $isNew, $this->params['db']);
					if($field == 'testcase_module'){
						$this->tool->getElementId("testcase_module_testcase_type", array('testcase_module_id'=>$case[$field.'_id'], 'testcase_type_id'=>$case['testcase_type_id']));
					}
				}
				unset($case[$field]);
			}
		}
		
		$case_fields = array('testcase_type_id', 'testcase_module_id', 'testcase_testpoint_id', 'code', 'summary'=>'', 'testcase_category_id', 'testcase_source_id', 'come_from');
		$case_value = $this->tool->extractItems($case_fields, $case);
		$newCase = false;
		
		if(!empty($case['isactive']) && $case['isactive'] == ISACTIVE_INACTIVE){
			$case_value['isactive'] = ISACTIVE_INACTIVE;
			$case_id = $this->tool->getElementId('testcase', $case_value, array('code'), $newCase, $this->params['db']);
print_r("inactive: ".$case_id."\n<BR />");
			return array();
		}
		$case_id = $this->tool->getElementId('testcase', $case_value, array('code'), $newCase, $this->params['db']);
		if (!$newCase){
			print_r($case_value['code']." already existed \n<BR />");
		}
		if (!empty($case['tag']))
			$this->tag['element_id'][] = $case_id;
		
		$ver_fields = array('ver'=>1, 'auto_level_id'=>AUTO_LEVEL_MANUAL, 'testcase_priority_id'=>TESTCASE_PRIORITY_P3, 'auto_run_minutes'=>0, 'manual_run_minutes'=>0, 'command'=>' ', 
			'objective'=>'(empty)', 'precondition'=>'(empty)', 'steps'=>'(empty)', 'expected_result'=>'(empty)', 'resource_link'=>'(empty)', 'parse_rule_id'=>1, 'parse_rule_content'=>' ', 
			'owner_id', 'updater_id');
		if(!empty($case["update_comment"]))
			$ver_fields[] = "update_comment";
		$ver_values = $this->tool->extractItems($ver_fields, $case);
		$ver_values['testcase_id'] = $case_id;
		//update cur summary & code to version
		$ver_values['associated_code'] = $case_value['code'];
		$ver_values['associated_summary'] = $case_value['summary'];
		
		$key_fields = array_keys($ver_values);
		unset($key_fields[0]);
		if(!empty($this->params["owner_id"])){
			$users = $this->userAdmin->getUserTable();
			$resUser = $this->tool->query("SELECT id, nickname FROM $users WHERE id='".$this->params["owner_id"]."'");
			$rowUser = $resUser->fetch();
			$this->params["owner"] = $rowUser["nickname"];
		}
		if(empty($this->params["owner"]))
			$this->params["owner"] = $this->userInfo->nickname;
		if(!empty($ver_values['update_comment']))
			$ver_values['update_comment'] = "[".$this->params["owner"]." ".date("Y-m-d H:i:s")."] update by document: ".$ver_values['update_comment'];
		else
			$ver_values['update_comment'] = "[".$this->params["owner"]." ".date("Y-m-d H:i:s")."] update by document";
		$ver_values['edit_status_id'] = EDIT_STATUS_PUBLISHED;
		$newVer = false;
		$version_id = $this->tool->getElementId('testcase_ver', $ver_values, $key_fields, $newVer, $this->params['db']);
// print_r($newVer);
		if ($newVer && !$newCase){
			$res = $this->tool->query("SELECT max(ver) as max_ver FROM testcase_ver WHERE testcase_id=$case_id");
			$row = $res->fetch();
			$max_ver = $row['max_ver'];
			$this->tool->update('testcase_ver', array('ver'=>$max_ver + 1, 'created'=>date('Y-m-d H:i:s')), "id=$version_id");
		}
		return array('testcase_id'=>$case_id, 'testcase_ver_id'=>$version_id);
		
	}
	
	protected function processPrjCaseVer($caseInfo, $verInfo){
		$res = $this->tool->query("SELECT id as testcase_ver_id, testcase_id, owner_id, testcase_priority_id, edit_status_id, auto_level_id".
			" FROM testcase_ver WHERE id={$verInfo['testcase_ver_id']}");
		$ver = $res->fetch();
		
		$prj_ids = array();
		if (!isset($caseInfo['prj_ids'])){
			if (isset($caseInfo['platform']) && isset($caseInfo['os'])){
				$platforms = explode(';', $caseInfo['platform']);
				$os = $caseInfo['os'];
				foreach($platforms as $platform){
					if (empty($platform))
						continue;
					// Need to uniform platform and board_type, use "-" to uniform them
					$prj_id = $this->getProject(trim($platform), $os, $isNew);
					$prj_ids[$prj_id] = $prj_id;
				}
			}
			else{
				$prj_ids = $this->params['prj_ids'];
			}
		}
		else
			$prj_ids = $caseInfo['prj_ids'];
		if(empty($prj_ids)){
print_r("\n<br />No Projects to Link!");
			return;
		}
		foreach($prj_ids as $prj_id){
			$link = $ver;
			$link['prj_id'] = $prj_id;
			$this->updatePrjCaseVer($link);
		}
	}
	
	protected function getProject($platform, $os, &$isNew){
		$board_type = '3DS';
		$platform = str_replace("/\\", '', $platform);
		preg_match('/^(.*)[_-](.*?)$/', $platform, $matches);
		if (!empty($matches)){
			$platform = $matches[1];
			$board_type = $matches[2];
		}
		$platform = trim($platform);
		$board_type = trim($board_type);
		$os = trim($os);
		$os_id = $this->tool->getElementId('os', array('name'=>$os), array('name'));
		$chip_id = $this->tool->getElementId('chip', array('name'=>$platform), array('name'));
		$board_type_id = $this->tool->getElementId('board_type', array('name'=>$board_type), array('name'));
		$name = $platform.'-'.$board_type.'-'.$os;
		$prj_id = $this->tool->getElementId('prj', compact('name', 'board_type_id', 'os_id', 'chip_id'), array('name'), $isNew, $this->params['db']);
		return $prj_id;
	}
	
	protected function getId($table, $valuePair, $keyFields = array(), &$is_new = true){
		static $elements = array();
		$cached = false;
		if (!empty($keyFields)){
			if(in_array('code', $keyFields)){
				$cached = true;
				foreach($keyFields as $k=>$v){
					if($v == 'code')
						$keyField = $keyFields[$k];
				}
			}
			else if(in_array('name', $keyFields)){
				$cached = true;
				foreach($keyFields as $k=>$v){
					if($v == 'name')
						$keyField = $keyFields[$k];
				}
			}
		}
		if (!$cached || empty($elements[$table][$valuePair[$keyField]])){
			$where = array();
			$realVP = array();
			$res = $this->tool->query("describe $table");
			while($row = $res->fetch()){
				if (isset($valuePair[$row['Field']]))
					$realVP[$row['Field']] = $valuePair[$row['Field']];
			}
// if($table == 'testcase_ver')
// print_r($realVP);
			if (empty($keyFields))
				$keyFields = array_keys($realVP);
			foreach($keyFields as $k){
				$where[] = "$k=:$k";
				$whereV[$k] = $realVP[$k];
			}
			$res = $this->tool->query("SELECT * FROM $table where ".implode(' AND ', $where), $whereV);
			if($row = $res->fetch()){
				$this->tool->update($table, $realVP, "id=".$row['id']);
				$is_new = false;
				return $row['id'];
			}
			return 'error';
			// $is_new = true;
			// $this->tool->insert($table, $realVP);
			// $element_id = $this->tool->lastInsertId();
			// if ($cached)
				// $elements[$table][$keyField] = $element_id;
			// return $element_id;
		}
		$is_new = false;
		return $elements[$table][$valuePair[$keyField]];
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
};
?>