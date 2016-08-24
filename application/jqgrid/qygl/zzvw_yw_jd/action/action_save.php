<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH.'/jqgrid/qygl/yw_tool.php');

class qygl_zzvw_yw_jd_action_save extends action_save{
	protected function fillDefaultValues($action, &$pair, $db, $table){
// print_r($this->params);		
		parent::fillDefaultValues($action, $pair, $db, $table);
		$hb = array();
		$res = $this->tool->query("SELECT id, name FROM hb WHERE id in ({$pair['hb_id']}, {$pair['jbr_id']})");
		while($row = $res->fetch())
			$hb[$row['id']] = $row['name'];
		$pair['name'] = $hb[$pair['jbr_id']].'在'.$pair['happen_date'].'接到'.$hb[$pair['hb_id']].'采购订单';
	}

}

?>