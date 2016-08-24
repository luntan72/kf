<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_th_detail_action_save extends action_save{
	protected function newRecord($db, $table, $pair){
// print_r($this->params);
// print_r($pair);		
		$affectedID = parent::newRecord($db, $table, $pair);

		$model = new Application_Model_Yw($this->params);
		$chuku = array('yw_id'=>$pair['yw_id'], 'gx_id'=>GX_CG, 'wz_id'=>$this->params['wz_id'], 'defect_id'=>1, 'amount'=>$pair['amount'], 'dingdan_id'=>$pair['dingdan_id'], 'happen_date'=>$this->params['happen_date']);
		$model->chuku($chuku, $pair['pici_id'], YW_FL_TH);
		return $affectedID;
	}
}

?>