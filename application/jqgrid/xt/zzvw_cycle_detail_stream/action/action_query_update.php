<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_stream_action_query_update extends xt_zzvw_cycle_detail_action_jqgrid{

	public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$real_table = $this->get('real_table');
		$where = 'detail.cycle_id = '.$params['parent'];
		// if(!empty($params['id']))
		// {
			// $element = $this->caclIDs($params);
			// if($element == "error")
				// return "error";
			$where .= " AND detail.codec_stream_id != 0";
			$sql = "select distinct detail.prj_id as prj_id from cycle_detail detail".
				" where {$where}";
			$res = $this->tool->query($sql);
			while($row = $res->fetch()){
				$ver_sql = "update cycle_detail detail left join prj_testcase_ver ptv on detail.testcase_id = ptv.testcase_id".
					" left join testcase_ver on testcase_ver.id = prj_testcase_ver.testcase_ver_id"
					" set detail.testcase_ver_id = ptv.testcase_ver_id".
					" where detail.cycle_id = {$params['parent']} and ptv.prj_id = {$row['prj_id']}".
					" and testcase_ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.", ".EDIT_STATUS_GOLDEN.")";
// print_r($ver_sql);
				$this->tool->query($ver_sql);
			}
			return 'success';
		// }
		
	}
}

?>