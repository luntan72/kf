<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_dingdan_jfjh_action_save extends action_save{
	private $model = null;
	
	protected function init(&$controller){
		parent::init($controller);
		$this->model = new Application_Model_Yw($controller, array());
	}

	protected function newRecord($db, $table, $pair){
// print_r($this->params);		
		switch($this->params['yw_fl_id']){
			case YW_FL_SH:
				$affectedID = $this->model->sh($this->params);
				break;
			case YW_FL_TH:
				$affectedID = $this->model->th($pair);
				break;
			case YW_FL_FH:
				$affectedID = $this->model->fh($pair);
				break;
			case YW_FL_JTH:
				$affectedID = $this->model->jth($this->params);
				break;
		}
	}
}