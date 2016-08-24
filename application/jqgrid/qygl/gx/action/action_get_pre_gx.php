<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_gx_action_get_pre_gx extends action_jqgrid{ //根据批次获取可用的物资，主要是为分解工序服务
	protected function handlePost(){
		$ret = array();
		$sql = "SELECT gx.* from gx_pre_gx left join gx on gx.id=gx_pre_gx.pre_gx_id where gx_pre_gx.gx_id={$this->params['gx_id']}";
// print_r($sql);			
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			$ret[$row['id']] = $row;
		}
		return json_encode($ret);
	}
}
