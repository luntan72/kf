<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_kp_action_save extends action_save{
	protected function fillDefaultValues($action, &$pair, $db, $table){
		parent::fillDefaultValues($action, $pair, $db, $table);
		$res = $this->tool->query("SELECT name FROM hb WHERE id={$pair['hb_id']}");
		$hb = $res->fetch();
		
		$res = $this->tool->query("SELECT name FROM yw_fl WHERE id={$pair['yw_fl_id']}");
		$yw_fl = $res->fetch();
		$name = $pair['happen_date'].'给'.$hb['name'].'开'.$this->params['from_date'].'到'.$this->params['to_date'].'的发票, 总金额'.$this->params['amount'].'元';
		$pair['name'] = $name;
		$this->params['fp_name'] = $name;
	}
	
	protected function prepareForOne2One($data){
		$data['hb_id'] = $this->params['hb_id'];
		$data['remained_amount'] = $this->params['amount'];
		$data['in_or_out'] = FP_IN_OR_OUT_XS; //销售发票
		$data['summary'] = $this->params['fp_name'];
		return $data;
	}
}
?>