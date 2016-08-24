<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");
require_once(APPLICATION_PATH.'/jqgrid/qygl/yw_tool.php');

class qygl_zzvw_pici_action_get_pici_by_dingdan_for_yunshu extends action_jqgrid{
	protected function handlePost(){
// print_r($this->params);
		$pici = array();
		switch($this->params['yw_fl_id']){
			case YW_FL_FH:
				$sql = " SELECT * FROM zzvw_pici WHERE wz_id={$this->params['wz_id']} AND gx_id=".GX_LAST." AND defect_id=1 AND remained>0";
				break;
			case YW_FL_SH:
				$sql = " SELECT * FROM zzvw_pici WHERE dingdan_id={$this->params['dingdan_id']} AND defect_id=1 AND amount>remained";
				break;
			case YW_FL_TH:
				$sql = " SELECT * FROM zzvw_pici WHERE dingdan_id={$this->params['dingdan_id']} AND remained>0"; //已经收到过货
				break;
			case YW_FL_JTH:
				$sql = " SELECT * FROM zzvw_pici WHERE dingdan_id={$this->params['dingdan_id']} AND defect_id=1 AND amount>remained"; //已经发过货
				break;
				
		}
// print_r($sql);
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			$pici[] = $row;
		}
		return $pici;
	}
}
