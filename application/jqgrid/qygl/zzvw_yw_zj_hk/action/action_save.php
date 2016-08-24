<?php 
require_once(APPLICATION_PATH.'/jqgrid/qygl/yw/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_zj_hk_action_save extends qygl_yw_action_save{
	private $model = null;
	
	protected function generateName($pair, $hb, $yw_fl){
		$res = $this->tool->query("SELECT * FROM zj_cause WHERE id={$this->params['zj_cause_id']}");
		$zj_cause = $res->fetch();
		$name = $pair['happen_date'].'收到'.$hb['name'].$zj_cause['name'].'款项'.$this->params['amount'].'元';
		return $name;
	}
	
	protected function afterSave($affectID){
		$ret = parent::afterSave($affectID);
		//更新账户余额
		$sql = "UPDATE zjzh set remained=remained+{$this->params['amount']} WHERE id={$this->params['zjzh_id']}";
		$this->tool->query($sql);
		
		$this->model = new Application_Model_Yw($this->params);
		//更新交易人的应收款信息以及账户余额
		$this->model->decYSK($this->params['hb_id'], $this->params['amount']);
		
		//处理zj_pj表
		if($this->params['zj_fl_id'] != ZJ_FL_XIANJIN){
			$data = $this->params;
			$data['total_money'] = $this->params['amount'];
			$data['from_yw_id'] = $affectID;
			$data['db'] = 'qygl';
			$data['table'] = 'zj_pj';
			$save_action = actionFactory::get(null, 'save', $data);
			$save_action->handlePost();
		}
		return $ret;
	}
}

?>