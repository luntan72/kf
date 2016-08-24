<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");
require_once(APPLICATION_PATH.'/jqgrid/qygl/yw_tool.php');

class qygl_dingdan_action_get_dingdan_by_hb extends action_jqgrid{
	protected function handlePost(){
		$dingdan = array();
		switch($this->params['yw_fl_id']){
			case YW_FL_FH://发货
				$sql = "SELECT * FROM zzvw_dingdan WHERE hb_id={$this->params['value']} and dingdan_status_id=".DINGDAN_STATUS_ZHIXING." and yw_fl_id=".YW_FL_JD;
				break;
			case YW_FL_SH: //收货
				$sql = "SELECT * FROM zzvw_dingdan WHERE hb_id={$this->params['value']} and dingdan_status_id=".DINGDAN_STATUS_ZHIXING." and yw_fl_id=".YW_FL_XD;
				break;
			case YW_FL_TH: //退货
				$sql = "SELECT zzvw_dingdan.* FROM zzvw_dingdan left join pici on zzvw_dingdan.id=pici.dingdan_id ".
					"WHERE zzvw_dingdan.hb_id={$this->params['value']} and zzvw_dingdan.completed_amount>0 and pici.remained>0 and zzvw_dingdan.yw_fl_id=".YW_FL_XD.
					" GROUP BY zzvw_dingdan.id ";//已经收到过货
				break;
			case YW_FL_JTH: //接退货
				$sql = "SELECT * FROM zzvw_dingdan WHERE hb_id={$this->params['value']} and completed_amount>0 and yw_fl_id=".YW_FL_JD; //已经发过货
				break;
				
		}
// print_r($sql);
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			$remained = $row['amount'] - $row['completed_amount'];
			$row['name'] = "[{$row['defect']}]的[{$row['wz_name']}]{$row['amount']}{$row['unit_name']}, ".
				"已完成{$row['completed_amount']}{$row['unit_name']}, 尚余===$remained==={$row['unit_name']}";
			$dingdan[] = $row;
		}
		return $dingdan;
	}
}
