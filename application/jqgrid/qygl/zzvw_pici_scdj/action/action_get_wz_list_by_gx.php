<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

/*
在一个确定的工序，有哪些产品是允许的？
1. 如果有前置工序，则前置工序的现存数量必须>0
2. 如果是组合工序，则前置工序的零件数量必须>0
3. 
*/
class qygl_zzvw_pici_scdj_action_get_wz_list_by_gx extends action_jqgrid{ //根据工序获取可用的产品
	protected function handlePost(){
		$res = $this->tool->query("SELECT * FROM gx WHERE id={$this->params['gx_id']}");
		$gx = $res->fetch();
		//检查是否有前置工序
		$pre_gx_ids = '';
		$res = $this->tool->query("SELECT group_concat(pre_gx_id) as pre_gx_ids FROM gx_pre_gx WHERE gx_id={$this->params['gx_id']}");
		if($row = $res->fetch())
			$pre_gx_ids = $row['pre_gx_ids'];
// print_r($pre_gx_ids);		
		//如果有前置工序，则只能从前置工序的产出品种选择
		if(!empty($pre_gx_ids)){
			if($gx['gx_fl_id'] != GX_FL_ZUHE){
				$sql = "SELECT DISTINCT wz.*, unit.name as unit_name, defect_gx_wz.remained".
					" FROM defect_gx_wz ".
					" LEFT JOIN gx_wz on gx_wz.id=defect_gx_wz.gx_wz_id".
					" LEFT JOIN wz on gx_wz.wz_id=wz.id".
					" LEFT JOIN unit on wz.unit_id=unit.id".
					" WHERE gx_wz.gx_id in ($pre_gx_ids) and defect_gx_wz.remained>0";
				if(!empty($this->params['defect_id']))
					$sql .= " AND defect_gx_wz.defect_id={$this->params['defect_id']}";
			}
			else{//组合工序
				$sql = "SELECT wz.* ".
					" FROM zzvw_wz_cp wz".
					" WHERE isactive=1 and zuhe=2";
			}
		}
		else{//否则，则从所有产品中选择
			$sql = "SELECT wz.* ".
				" FROM zzvw_wz_cp wz".
				" WHERE isactive=1";
			if($gx['gx_fl_id'] == GX_FL_ZUHE)
				$sql .= " AND zuhe=2";
		}
		$sql .= " GROUP BY wz.id";
// print_r($sql);		
		$ret = array();
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			$ret[$row['id']] = $row;
		}
		return json_encode($ret);
	}
}
