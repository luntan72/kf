<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_fp_action_save extends action_save{
	protected function afterSave($affectedID){
		$ret = parent::afterSave($affectedID);
		if(!empty($this->params['cyr_id']) && !empty($this->params['yunfei'])){ //增加承运人的应付款
			$model = new Application_Model_Yw($this->params);
			$model->decYSK($this->params['cyr_id'], $this->params['yunfei']);
		}
		return $ret;
	}
}
?>