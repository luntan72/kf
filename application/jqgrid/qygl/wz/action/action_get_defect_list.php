<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_wz_action_get_defect_list extends action_jqgrid{
	protected function handlePost(){
// print_r($this->params);		
		$ret = array();
		$sql = "SELECT defect.id, defect.name from defect left join defect_gx_wz on defect.id=defect_gx_wz.defect_id ".
			" left join gx_wz on gx_wz.id=defect_gx_wz.gx_wz_id ".
			" WHERE wz_id={$this->params['wz_id']} and gx_id={$this->params['gx_id']}".
			" group by defect.id";
		$res = $this->tool->query($sql);
		if($row = $res->fetch())
			$ret[] = $row;
		if(empty($ret))
			$ret[] = array('id'=>1, 'name'=>'正品，无缺陷');
		return $ret;
	}
}
