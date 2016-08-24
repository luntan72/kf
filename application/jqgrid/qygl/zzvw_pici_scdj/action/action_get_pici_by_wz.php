<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_zzvw_pici_scdj_action_get_pici_by_wz extends action_jqgrid{
	protected function handlePost(){
		$ret = array(
			CHUKU_STRATEGY_USE_OLDEST=>array('id'=>CHUKU_STRATEGY_USE_OLDEST, 'name'=>'按先旧后新顺序的批次', 'remained'=>0),
			CHUKU_STRATEGY_USE_NEWEST=>array('id'=>CHUKU_STRATEGY_USE_NEWEST, 'name'=>'按先新后旧顺序的批次', 'remained'=>0),
			);
// print_r($this->params)		;
		if(empty($this->params['gx']) || $this->params['gx'] == 'last')
			$this->params['gx'] =  GX_LAST;
		$sql = "SELECT * FROM zzvw_pici_scdj WHERE wz_id={$this->params['wz_id']} AND gx_id={$this->params['gx']} and remained>0 order by happen_date ASC";
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			// if(empty($row['defect_id']))
				// $row['defect'] = '正品，没有缺陷';
			// $row['name'] = $row['name']."[{$row['defect']}]剩余{$row['remained']}";
			$ret[$row['id']] = $row;
			$ret[CHUKU_STRATEGY_USE_OLDEST]['remained'] += $row['remained']; //
			$ret[CHUKU_STRATEGY_USE_NEWEST]['remained'] += $row['remained']; //
		}
		return json_encode($ret);
	}
}
