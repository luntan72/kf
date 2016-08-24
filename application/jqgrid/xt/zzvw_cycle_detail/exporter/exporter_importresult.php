<?php
require_once('dbfactory.php');
require_once('exporter_excel.php');

class xt_zzvw_cycle_detail_exporter_importresult extends exporter_excel{
	protected function getTitle($table_desc){
		$title = array();
		$exportFields = array(
			'id'=>array('hidden'=>true),
			'prj_id'=>array('hidden'=>true),
			'chip_id'=>array('width'=>70, 'hidden'=>false),
			'board_type_id'=>array('width'=>70, 'hidden'=>false),
			'os_id'=>array('width'=>80, 'hidden'=>false),
			'compiler_id'=>array('width'=>60, 'hidden'=>false),
			'build_target_id'=>array('width'=>60, 'hidden'=>false),
			'test_env_id'=>array('width'=>80, 'hidden'=>true),
			'testcase_module_id'=>array('width'=>150, 'hidden'=>false),
			'd_code'=>array('width'=>150, 'hidden'=>false),
			'build_result_id'=>array('width'=>60, 'hidden'=>false),
			'result_type_id'=>array('width'=>60, 'hidden'=>false),
			'comment',
			'defect_ids'=>array('label'=>'CRID', 'hidden'=>false),
			'tester_id'=>array('hidden'=>false, 'width'=>80),
			'summary',
			'testcase_testpoint_id'=>array('hidden'=>true),
			'testcase_category_id'=>array('hidden'=>true),
			'testcase_priority_id'=>array('cols'=>1, 'hidden'=>false, 'width'=>60),
			'auto_level_id'=>array('hidden'=>true),
			'precondition'=>array('hidden'=>true, 'width'=>500),
			'objective'=>array('hidden'=>true, 'width'=>500),
			'steps'=>array('hidden'=>true, 'width'=>500)
		);
		$options = $table_desc->getOptions();
		$colModelMap = $options['gridOptions']['colModelMap'];
		$colModel = $options['gridOptions']['colModel'];
		$useradmin = dbfactory::get("useradmin");
		$res = $useradmin->query("select id, username from users");
		while($info = $res->fetch()){
			$userlist[] = $info['id'].':'.$info['username'];
		}
		foreach($exportFields as $k=>$v){
			$tester_field = false;
			
			if(is_int($k)){
				$k = $v;
				$v = array('hidden'=>false);
			}
			if('tester_id' == $k){
				$tester_field = true;
			}
			$orig = $colModel[$colModelMap[$k]];
			if($tester_field){
				$orig['editoptions']['value'] = $orig['formatoptions']['value'] = 
					$orig['searchoptions']['value'] = implode(";", $userlist);
			}
			$title[] = array_merge($orig, $v);
		}
		return $title;
	}
};

?>
