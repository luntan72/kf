<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_sh_detail_action_save extends action_save{
	protected function newRecord($db, $table, $pair){
// print_r($this->params);
// print_r($pair);		
		$affectedID = parent::newRecord($db, $table, $pair);

		$model = new Application_Model_Yw($this->params);
		$ruku =$pair;//
		$ruku['gx_id'] = GX_CG;
		$ruku['wz_id'] = $this->params['wz_id'];
		$ruku['happen_date'] = $this->params['happen_date'];
		$model->ruku($ruku, YW_FL_SH);

		return $affectedID;
	}
}

?>