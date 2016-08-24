<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_zzvw_pici_scdj_action_get_pici_list extends action_jqgrid{ //根据工序获取可用的输入批次，主要是为分解工序服务
	protected function handlePost(){
		$ret = array();
		$res = $this->tool->query("SELECT * from gx where id={$this->params['gx_id']}");
		$gx = $res->fetch();
		$res = $this->tool->query("SELECT group_concat(pre_gx_id) as pre_gx_ids FROM gx_pre_gx WHERE gx_id={$this->params['gx_id']}");
		if($row = $res->fetch()){
// print_r($row);			
			$sql = "SELECT zzvw_pici_scdj.*, unit.name as unit_name ".
				" FROM zzvw_pici_scdj left join wz on zzvw_pici_scdj.wz_id=wz.id left join unit on wz.unit_id=unit.id".
				" WHERE gx_id in ({$row['pre_gx_ids']}) AND zzvw_pici_scdj.remained>0 AND wz.isactive=1 ";
			if($gx['gx_fl_id'] == GX_FL_ZUHE)
				$sql .= " AND wz.zuhe=2";
			$sql .= " order by happen_date ASC";
// print_r($sql);				
			$res = $this->tool->query($sql);
			while($row = $res->fetch()){
				$row['name'] = $row['name'].", 剩余{$row['remained']}";
				$ret[$row['id']] = $row;
			}
		}
		return json_encode($ret);
	}
}
