<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_zzvw_pici_scdj_action_get_wz_detail_for_fenjie extends action_jqgrid{ //根据批次获取可用的物资，主要是为分解工序服务
	protected function handlePost(){
		$ret = array();
		$sql = "SELECT wz.*, unit.name as unit_name, zzvw_pici_scdj.remained ".
			" FROM wz_cp_zuhe left join zzvw_pici_scdj on zzvw_pici_scdj.wz_id=wz_cp_zuhe.wz_id".
			" left join wz on wz_cp_zuhe.input_wz_id=wz.id left join unit on wz.unit_id=unit.id".
			" WHERE zzvw_pici_scdj.id={$this->params['id']}";
// print_r($sql);			
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			$ret[$row['id']] = $row;
			// //从产品组合表获取产品的组合情况
			// $t = $this->tool->query("SELECT * FROM wz_cp_zuhe WHERE wz_id={$row['id']}");
			// while($t_row = $t->fetch()){
				// $ret[$t_row['input_wz_id']] = $t_row;
			// }
		}
		return json_encode($ret);
	}
}
