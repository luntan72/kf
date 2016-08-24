<?php
require_once('toolfactory.php');
require_once('const_def.php');

class xt_common{
	protected $tool = null;
	protected $params = array();
	public function __construct($params){
		$this->init($params);
	}
	
	protected function init($params){
		$this->params = $params;
		$this->tool = toolFactory::get();
		$this->tool->setDb($this->params['db']);
		if(empty($this->params['root']))
			$this->params['root'] = "C:\\Users\\b19268\\xampp\\kf";
	}
	
	public function test(){
		print_r($this->params);
		$this->tool->setDb($this->params['db']);
		$res = $this->tool->query("select * from board_type");
		$row = $res->fetch();
		print_r($row);
	}
	
	public function createPrj($prjInfo){
		$trans = array('board_type', 'chip', 'os');
		foreach($trans as $each){
			if(empty($prjInfo[$each.'_id']))
				$prjInfo[$each.'_id'] = $this->tool->getElementId($each, array('name'=>$prjInfo[$each]));
		}
		$prjInfo['name'] = $prjInfo['chip'].'-'.$prjInfo['board_type'].'-'.$prjInfo['os'];
		$vp = array('name'=>$prjInfo['name'], 'chip_id'=>$prjInfo['chip_id'], 'board_type_id'=>$prjInfo['board_type_id'], 'os_id'=>$prjInfo['os_id']);
// print_r($vp);		
		$prjInfo['id'] = $this->tool->getElementId('prj', $vp, array('chip_id', 'board_type_id', 'os_id'));
		return $prjInfo;
	}

	public function generateCycleName($cycleInfo){
		$start_date = strtotime($cycleInfo['start_date']);
		$currentYear = (int)date('y', $start_date);
		$currentWorkWeek = (int)date('W', $start_date);
		$week = sprintf("%2dWK%02d", $currentYear, $currentWorkWeek);
		if (empty($cycleInfo['myName'])){
			$cycleInfo['myName'] = date('Ymd', $start_date);
		}
		return $cycleInfo['prj'].'-'.$cycleInfo['cycle_type'].'-'.$week.'-'.$cycleInfo['myName'];
	}
	
	public function createCycle(&$cycleInfo){
		$trans = array('prj', 'rel', 'compiler'=>'GCC', 'cycle_type'=>'Fun', 'build_target'=>'Release', 'testcase_type');
		foreach($trans as $table=>$default){
			if(is_int($table)){
				$table = $default;
				$default = 0;
			}
			$id = $table.'_id';
			if(!isset($cycleInfo[$table]) && !empty($default))
				$cycleInfo[$table] = $default;
			if(!isset($cycleInfo[$id])){
				$cycleInfo[$id] = $this->tool->getElementId($table, array('name'=>$cycleInfo[$table]));
			}
		}
		$trans = array('prj', 'compiler', 'build_target', 'testcase_type');
		foreach($trans as $e){
			if(!isset($cycleInfo[$e.'_ids']))
				$cycleInfo[$e.'_ids'] = $cycleInfo[$e.'_id'];
		}
		
		if (empty($cycleInfo['start_date']))
			$cycleInfo['start_date'] = date('Y-m-d');
		if (!isset($cycleInfo['name'])){
			$cycleInfo['name'] = $this->generateCycleName($cycleInfo);
		}
//print_r($cycleInfo);		
		$new = false;
		$cycle_id = $this->tool->getElementId('cycle', $cycleInfo, $new);
		if(!empty($cycleInfo['logfile'])){
			$root = $this->params['root'];
			$dir = $root."\\data\\log\\{$cycle_id}";
			$this->tool->createDirectory($dir);
			$file = $dir."\\readme.txt";
			if($fh = fopen($file, "wb")){
				fwrite($fh, "Created with logfile: {$cycleInfo['logfile']}");
				fclose($fh);
			}
		}
		return $cycle_id;
	}
	
	public function insertCycleDetail($cycleInfo, $cycleDetail){
		foreach($cycleDetail as $detail){
			$detail['cycle_id'] = $cycleInfo['id'];
			$detail['test_env_id'] = $cycleInfo['test_env_id'];
			$trans = array('prj', 'compiler', 'build_target');
			foreach($trans as $e){
				if(!isset($detail[$e.'_id']))
					$detail[$e.'_id'] = $cycleInfo[$e.'_ids'];
			}
			if(!isset($detail['testcase_id']) || !isset($detail['testcase_ver_id'])){
				//从testcase name计算id
				$res = $this->tool->query("SELECT prj_testcase_ver.testcase_id, prj_testcase_ver.testcase_ver_id ".
					" FROM prj_testcase_ver left join testcase on prj_testcase_ver.testcase_id=testcase.id ".
					" left join testcase_ver on prj_testcase_ver.testcase_ver_id=testcase_ver.id ".
					" WHERE prj_id=:prj_id AND code=:code and testcase_ver.edit_status_id in (1,2)", array('prj_id'=>$detail['prj_id'], 'code'=>$detail['testcase']));
				$row = $res->fetch();
				$detail['testcase_id'] = $row['testcase_id'];
				$detail['testcase_ver_id'] = $row['testcase_ver_id'];
			}
			$detail_id = $this->tool->insert('cycle_detail', $detail);
			if(isset($detail['logfile'])){ //把logfile里的内容保存到文件里
				$root = $this->params['root'];
				$dir = $root."\\data\\log\\{$cycleInfo['id']}\\{$detail_id}";
				$this->tool->createDirectory($dir);
				$filename = $this->tool->formatFileName($dir."\\log.txt", 'txt');
				if($fh = fopen($filename, "wb")){
					fwrite($fh, $detail['logfile']);
					fclose($fh);
				}
			}
		}
	}
	
	public function delCycle($cycle_id){
		$this->tool->delete('cycle_detail', "cycle_id=$cycle_id");
		$this->tool->delete('cycle', "id=$cycle_id");
	}
	
	public function delRel($rel_id){
		$this->tool->delete('rel', "id=$rel_id");
		$this->tool->delete('os_rel', "rel_id=$rel_id");
	}
	
	public function createRelease($relInfo, $os_id){
		$rel_id = $this->tool->getElementId('rel', $relInfo);
		$this->tool->getElementId('os_rel', array('rel_id'=>$rel_id, 'os_id'=>$os_id));
		return $rel_id;
	}
	
	public function getCaseInfo($case){
		if (!isset($case['testcase_testpoint']) && isset($case['testcase_module']))
			$case['testcase_testpoint'] = 'Default testpoint for '.$case['testcase_module'];
		$testcase = array('code'=>$case['code']);
		if (isset($case['summary']))
			$testcase['summary'] = $case['summary'];
		else
			$testcase['summary'] = 'Summary for '.$case['code'];

		$transfer_fields = array('testcase_type'=>'Linux BSP', 'testcase_module', 'testcase_testpoint', 'testcase_category'=>'Function', 'testcase_source'=>'FSL MAD');
		foreach($transfer_fields as $field=>$defaultValue){
			$hasDefaultValue = true;
			if (is_int($field)){
				$field = $defaultValue;
				$hasDefaultValue = false;
			}
			if(isset($case[$field.'_id']))
				$testcase[$field.'_id'] = $case[$field.'_id'];
			else{
				if(!isset($case[$field]) && $hasDefaultValue)
					$case[$field] = $defaultValue;
				if (isset($case[$field])){
					$v = array('name'=>$case[$field]);
					if ($field == 'testcase_module')
						$v['testcase_type_ids'] = $testcase['testcase_type_id'];
					elseif ($field == 'testcase_testpoint')
						$v['testcase_module_id'] = $testcase['testcase_module_id'];
					$testcase[$field.'_id'] = $this->getElementId($field, $v);
				}
			}
		}
		$newCase = false;
		$case_id = $this->getElementId('testcase', $testcase, array('code'), $newCase);
		
		$version = array('testcase_id'=>$case_id, 'edit_status_id'=>EDIT_STATUS_PUBLISHED);
		$fields = array('testcase_priority_id'=>3, 'auto_level_id'=>AUTO_LEVEL_MANUAL, 'command'=>'', 'objective'=>'', 'precondition'=>'', 
			'steps'=>'', 'expected_result'=>'', 
			'parse_rule_id'=>1, 'parse_rule_content'=>'', 'auto_run_seconds'=>0, 'manual_run_seconds'=>0,
			//'created'=>date('Y-m-d H:i:s'), 'update_comment'=>'', 'review_comment'=>''
			);
		foreach($fields as $field=>$defaultValue){
			$version[$field] = isset($case[$field]) ? $case[$field] : $defaultValue;
		}
		$newVer = false;
		$version_id = $this->getElementId('testcase_ver', $version, array(), $newVer);
		if ($newVer && !$newCase){
			$res = $this->db->query("SELECT max(ver) as max_ver FROM testcase_ver WHERE testcase_id=$case_id");
			$row = $res->fetch();
			$max_ver = $row['max_ver'];
			$this->db->update('testcase_ver', array('ver'=>$max_ver + 1, 'created'=>date('Y-m-d H:i:s')), "id=$version_id");
		}
		return array('testcase_id'=>$case_id, 'testcase_ver_id'=>$version_id);
	}	
	
	protected function prj_testcase_ver($prj_id, $testcase_ver){
		$v = array('prj_id'=>$prj_id, 'testcase_id'=>$testcase_ver['testcase_id'], 'testcase_ver_id'=>$testcase_ver['id'], 
			'owner_id'=>$testcase_ver['owner_id'], 'testcase_priority_id'=>$testcase_ver['testcase_priority_id'],
			'edit_status_id'=>$testcase_ver['edit_status_id'], 'auto_level_id'=>$testcase_ver['auto_level_id']);
		$new = false;
		$link_id = $this->getElementId('prj_testcase_ver', $v, array(), $new);
//print_r("link_id = $link_id, new = $new, testcase_ver = ");		
//print_r($testcase_ver);	
		if ($new == true){
			$v = array('prj_id'=>$prj_id, 'testcase_id'=>$testcase_ver['testcase_id'], 'testcase_ver_id'=>$testcase_ver['id'], 'act'=>'add');
			$this->db->insert('prj_testcase_ver_history', $v);
		}
	}
	
	public function getTestcaseInfo($testcase, $prj_ids){
		$caseInfo = $this->getCaseInfo($testcase);
		$res = $this->db->query("SELECT * FROM testcase_ver WHERE id=".$caseInfo['testcase_ver_id']);
		$ver = $res->fetch();
		foreach($prj_ids as $prj_id){
			$this->prj_testcase_ver($prj_id, $ver);
		}
	}
	
	protected function getResultId($result){
		$result = strtolower($result);
			
		switch(strtolower($result)){
			case 'ok':
			case 'pass':
				$result = 'pass';
				break;
			case 'fail':
			case 'nok':
				$result = 'fail';
				break;
			case 'na':
			case 'n/a':
				$result = 'N/A';
				break;
			case 'nt':
			case 'n/t':
				$result = 'N/T';
				break;
			case 'ns':
			case 'n/s':
				$result = 'N/S';
				break;
		}
		if (stripos($result, 'not support') !== false)
			$result = 'N/S';
		
		return $this->getElementId('result_type', array('name'=>$result));
	}
	
	protected function getChipTypeId($chipName){
		$chip_type_id = 0;
		$os_mqx = $this->getElementId('os', array('name'=>'mqx'));
		$board_type_twr = $this->getElementId('board_type', array('name'=>'twr'));
		$board_type_autoevb = $this->getElementId('board_type', array('name'=>'autoevb'));
		if (preg_match('/^(i\.mx|mx|m[^x]|vf|px|k)(.*)/i', $chipName, $matches)){
//print_r($matches);		
			switch(strtolower($matches[1])){
				case 'i.mx':
				case 'mx':
					$chip_type = array('name'=>'MX'.$matches[2][0], 'os_ids'=>'1,2,3,4,5,6,7', 'board_type_ids'=>'1,2,3,4,5,6,7,8,9,10,11,12,13,14');
					break;
				case 'vf':
					$chip_type = array('name'=>'Vybrid', 'os_ids'=>$os_mqx, 'board_type_ids'=>$board_type_twr.','.$board_type_autoevb);
					break;
				case 'px':
					$chip_type = array('name'=>'PowerPC', 'os_ids'=>$os_mqx, 'board_type_ids'=>$board_type_twr.','.$board_type_autoevb);
					break;
				case 'k':
					$chip_type = array('name'=>'Kinetis', 'os_ids'=>$os_mqx, 'board_type_ids'=>$board_type_twr.','.$board_type_autoevb);
					break;
				default: // m?
					$chip_type = array('name'=>'ColdFire', 'os_ids'=>$os_mqx, 'board_type_ids'=>$board_type_twr.','.$board_type_autoevb);
					break;
			}
			$chip_type_id = $this->getElementId('chip_type', $chip_type);
		}
//print_r("chipname = $chipName, type_id = $chip_type_id\n");		
		return $chip_type_id;
	}
	
	public function getChipId($chipName){
		$chip_type_id = $this->getChipTypeId($chipName);
		return $this->getElementId('chip', array('name'=>$chipName, 'chip_type_id'=>$chip_type_id));
	}
	
	function excelTime($date, $time = false) {
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
	
	
};

?>