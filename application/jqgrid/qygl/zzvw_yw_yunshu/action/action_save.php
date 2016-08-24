<?php 
require_once(APPLICATION_PATH.'/jqgrid/qygl/yw/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_yunshu_action_save extends qygl_yw_action_save{
	protected function generateName($pair, $hb, $yw_fl){
		switch($this->params['yw_fl_id']){
			case YW_FL_SH://收货
				$name = "收到".$hb['name'].$pair['happen_date']."运送的货物";
				break;
			case YW_FL_FH://发货
				$name = $pair['happen_date']."由".$hb['name']."发送货物";
				break;
			case YW_FL_TH://退货
				$name = $pair['happen_date']."由".$hb['name']."退还货物";
				break;
			case YW_FL_JTH://接收退货
				$name = $pair['happen_date']."由".$hb['name']."运输发回的退货";
				break;
		}
		return $name;
	}
	
	protected function fillDefaultValues($action, &$pair, $db, $table){
		parent::fillDefaultValues($action, $pair, $db, $table);
		switch($this->params['yw_fl_id']){
			case YW_FL_SH://收货
				$detail = 'zzvw_yw_sh_detail';
				break;
			case YW_FL_FH://收货
				$detail = 'zzvw_yw_fh_detail';
				break;
			case YW_FL_TH://收货
				$detail = 'zzvw_yw_th_detail';
				break;
			case YW_FL_JTH://收货
				$detail = 'zzvw_yw_jth_detail';
				break;
			
		}
		if(!empty($this->params[$detail])){
			foreach($this->params[$detail]['data'] as &$data){
				$data['happen_date'] = $this->params['happen_date'];
				$data['yw_fl_id'] = $this->params['yw_fl_id'];
			}
		}
// print_r($this->params);	
	}

	protected function afterSave($affectID){
		$ret = parent::afterSave($affectID);
		$model = new Application_Model_Yw($this->params);
		
		//更新承运人和装卸人的应收款信息
		//运费
		$yunfei = floatval($this->params['weight']) * floatval($this->params['yunshu_price']);
		$model->decYSK($this->params['hb_id'], $yunfei);
		//装卸费
		if(!empty($this->params['zxr_id'])){
			$zhuangxiefei = floatval($this->params['weight']) * floatval($this->params['zx_price']);
			$model->decYSK($this->params['zxr_id'], $zhuangxiefei);
		}
		return $ret;
	}
}
?>