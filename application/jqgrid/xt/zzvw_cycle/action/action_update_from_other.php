<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_update_from_other extends action_jqgrid{
	protected function handlePost(){
		$testcaseLists = array();
		$res = $this->tool->query("select prj_id, build_target_id, compiler_id, testcase_id, result_type_id, comment, defect_ids, finish_time".
			" from cycle_detail".
			" where cycle_id=".$this->params['id'][0]." and result_type_id != ".RESULT_TYPE_BLANK);
		while($row = $res->fetch()){
			$cond = "cycle_id=".$this->params['parent']." and testcase_id=".$row['testcase_id']." and prj_id=".$row['prj_id'].
				" and build_target_id=".$row['build_target_id']." and compiler_id=".$row['compiler_id'];
			$new_res = $this->tool->query("select * from cycle_detail where ".$cond);
			if($new_row = $new_res->fetch()){
				if($this->params['replaced'] == 'false')
					$cond .= " and result_type_id=".RESULT_TYPE_BLANK;
				$data = array("result_type_id"=>$row['result_type_id'], 'comment'=>$row['comment'], 'defect_ids'=>$row['defect_ids'], 'finish_time'=>$row['finish_time']);
				$sql = "update cycle_detail set result_type_id={$row['result_type_id']}, finish_time='{$row['finish_time']}'";
				if(!empty($row['comment'])){
					$row['comment'] = mysql_real_escape_string($row['comment']);
					$sql .= ", comment='{$row['comment']}'";
				}
				if(!empty($row['defect_ids']))
					$sql .= ", defect_ids='{$row['defect_ids']}'";
				$this->tool->query($sql." where {$cond}");
			}
			else{
				$tmp_res = $this->tool->query("select * from testcase where id = ".$row['testcase_id']);
				if($tmp_row = $tmp_res->fetch()){
					$testcaseLists[] = $tmp_row['code'];
				}
			}
		}
		if(!empty($testcaseLists)){
			$testcaseList = json_encode($testcaseLists);
			return "Cases not in current cycle: ".$testcaseList;
		}
		else
			return "Update Successly";
	}
}

?>