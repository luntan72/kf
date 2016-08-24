<?php
require_once('importer_excel.php');

class xt_zzvw_cycle_importer_cycle extends importer_excel{
	
	protected function processCase($case){
		if(!empty($case['auto_level'])){
			if(strtolower($case['auto_level']) == 'semi-auto')
				$case['auto_level'] = 'Partial Auto';
			else if(strtolower($case['auto_level']) == 'cancel'){
				unset($case['auto_level']);
				$case['isactive'] = ISACTIVE_INACTIVE;
			}
		}
		if(strlen($case['testcase_priority']) == 1)
			$case['testcase_priority'] = 'P'.$case['testcase_priority'];
			
		if (!empty($case['owner'])){
			$table = $this->userAdmin->getUserTable();
			$res = $this->tool->query("SELECT id FROM $table WHERE nickname=:nick", array('nick'=>$case['owner']));
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
		$ver_values = $this->tool->extractItems($ver_fields, $case);
		$ver_values['testcase_id'] = $case_id;
		$key_fields = array_keys($ver_values);
		unset($key_fields[0]);
		$ver_values['update_comment'] = "update by document by ".$this->userInfo->nickname;
		$ver_values['edit_status_id'] = EDIT_STATUS_PUBLISHED;
		
		$newVer = false;
		$version_id = $this->tool->getElementId('testcase_ver', $ver_values, $key_fields, $newVer, $this->params['db']);
		if ($newVer && !$newCase){
			$res = $this->tool->query("SELECT max(ver) as max_ver FROM testcase_ver WHERE testcase_id=$case_id");
			$row = $res->fetch();
			$max_ver = $row['max_ver'];
			$this->tool->update('testcase_ver', array('ver'=>$max_ver + 1, 'created'=>date('Y-m-d H:i:s')), "id=$version_id");
		}
		$verInfo = array('testcase_id'=>$case_id, 'testcase_ver_id'=>$version_id);
		$this->processPrjCaseVer($case, $verInfo);
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
					//需要规范platform和board_type，连接符用-
					$prj_id = $this->getProject($platform, $os, $isNew);
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
	
	protected function getProject($platform, $os, &$isNew){
		$board_type = '3DS';
		$platform = str_replace("/\\", '', $platform);
		preg_match('/^(.*)[_-](.*?)$/i', $platform, $matches);
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
	
	public function excelTime($date, $time = false) {
		if(!stripos($date, "-")){
			if(preg_match('/^(\d{4})(\d{2})(\d{2})$/i', $date, $matches)){
				$date = $matches[1]."-".$matches[2]."-".$matches[3];
			}
		}
		else{
			if(preg_match('/^(\d{2})-(.*)-(\d{4})$/i', $date, $matches)){

				$months = array('01'=>'jan', '02'=>'feb', '03'=>'mar', '04'=>'apr', '05'=>'may', '06'=>'jun', '07'=>'jul', 
					'08'=>'aug', '09'=>'sep', '10'=>'oct', '11'=>'nov', '12'=>'dec');
				$key = array_search(strtolower($matches[2]), $months);
				if($key !== false)
					$matches[2] = $key;
				$date = $matches[3]."-".$matches[2]."-".$matches[1];
			}
		}
		if (function_exists('GregorianToJD')) {
			if (is_numeric($date)) {
				$jd = GregorianToJD(1, 1, 1970);
				$gregorian = JDToGregorian($jd + intval($date) - 25569);
				$date = explode('/', $gregorian);
				$date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date[1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
				return $date_str;
			}
		} else {
				$date = $date > 25568 ? $date + 1 : 25569;
				/*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
				$ofs = (70 * 365 + 17 + 2) * 86400;
				$date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
		}
		return $date;
	}
	
	public function readExcel($fileName){
		/**  Identify the type of $inputFileName  **/
		$inputFileType = PHPExcel_IOFactory::identify($fileName);
		/**  Create a new Reader of the type that has been identified  **/
		$reader = PHPExcel_IOFactory::createReader($inputFileType);
		$reader->setReadDataOnly(true);
		$objExcel = $reader->load($fileName);
		return $objExcel;
	}
}
?>