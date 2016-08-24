<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_ck_pd_action_save extends action_save{
	protected function afterSave($affectedId){
		$pici_id = $this->params['pici_id'];
		$data = array('remained'=>$this->params['amount'], 'id'=>$pici_id);
		if($this->params['amount'] == 0)
			$data['need_pd'] = 2;
		$this->tool->update('pici', $data);
	}
}

?>