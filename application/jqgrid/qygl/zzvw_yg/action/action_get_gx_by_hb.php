<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_zzvw_yg_action_get_gx_by_hb extends action_jqgrid{
	protected function handlePost(){
		$ret = 0;
		$sql = "SELECT DISTINCT gx.id FROM gx LEFT JOIN gx_work_type on gx.id=gx_work_type.gx_id left join hb_yg on gx_work_type.work_type_id=hb_yg.work_type_id WHERE hb_yg.hb_id={$this->params['hb_id']} limit 1";
		$res = $this->tool->query($sql);
		if($row = $res->fetch())
			$ret = $row['id'];
		return $ret;
	}
}
