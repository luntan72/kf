<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_jfp_action_save extends action_save{
	protected function fillDefaultValues($action, &$pair, $db, $table){
// print_r($pair);		
		parent::fillDefaultValues($action, $pair, $db, $table);
		$res = $this->tool->query("SELECT name FROM hb WHERE id={$pair['hb_id']}");
		$hb = $res->fetch();
		
		$res = $this->tool->query("SELECT name FROM yw_fl WHERE id={$pair['yw_fl_id']}");
		$yw_fl = $res->fetch();
		$name = $pair['happen_date'].'接到'.$hb['name'].'的发票, 业务期间从'.$this->params['from_date'].'到'.$this->params['to_date'].', 总金额'.$this->params['amount'].'元';
		$pair['name'] = $name;
		$this->params['fp_name'] = $name;
// print_r($pair);		
	}
	
	protected function prepareForOne2One($data){
		$data['hb_id'] = $this->params['hb_id'];
		$data['in_or_out'] = FP_IN_OR_OUT_CG;
		$data['summary'] = $this->params['fp_name'];
		$data['remained_amount'] = $this->params['amount'];
		return $data;
	}
}
?>