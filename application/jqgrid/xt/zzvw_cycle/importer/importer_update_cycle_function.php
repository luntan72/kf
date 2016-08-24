<?php
require_once('importer_base.php');

class xt_zzvw_cycle_importer_update_cycle_function extends importer_base{
	protected function _import($fileName){
		// $this->parse($fileName);
		return $this->process();
	}

	protected function process(){
print_r("process update"."\n<br />");
		$num = 0;
		$newcases = array();
		$res = $this->tool->query("select * from function_case_for_spring");
		while($row = $res->fetch()){
			$res0 = $this->tool->query("select testcase.code, prj.name as prj, cycle.name as cycle, result_type.name as result, finish_time, testcase_module.name as module from cycle_detail detail".
				" left join testcase on testcase.id = detail.testcase_id".
				" left join testcase_module on testcase_module.id = testcase.testcase_module_id".
				" left join result_type on result_type.id = detail.result_type_id".
				" left join prj on prj.id = detail.prj_id".
				" left join cycle on cycle.id = detail.cycle_id".
				" left join cycle_testcase_type on cycle_testcase_type.cycle_id = cycle.id".
				" left join cycle_prj on cycle_prj.cycle_id = cycle.id".
				" where cycle_testcase_type.testcase_type_id = 1 and cycle.created > '2015-10-07 00:00:00'".
				" and detail.testcase_id=".$row['testcase_id']." and detail.prj_id=".$row['prj_id']);
			while($row0 = $res0->fetch()){
				$this->tool->getElementId('function_result_for_spring', array("name"=>$row0['code'],"prj"=>$row0['prj'],
					"cycle"=>$row0['cycle'],"result"=>$row0['result'],"finish_time"=>$row0['finish_time'],"module"=>$row0['module']));
				$newcases[] = $row['id'];
			}
		}
		print_r(count($newcases));
		$newcases = array_unique($newcases);
		print_r(count($newcases));
		$res1 = $this->tool->query("select testcase.code, prj.name as prj, testcase_module.name as module from function_case_for_spring fcfs".
			" left join testcase on testcase.id = fcfs.testcase_id".
			" left join testcase_module on testcase_module.id = testcase.testcase_module_id".
			" left join prj on prj.id = fcfs.prj_id".
			" where fcfs.id not in (".implode(",", $newcases).")");
		while($row1 = $res1->fetch()){
			print_r("xxxxxxxxxxxxxxxx");
			$this->tool->getElementId('function_result_for_spring', array("name"=>$row1['code'],"prj"=>$row1['prj'],"module"=>$row1['module']));
		}
	}
}

?>
