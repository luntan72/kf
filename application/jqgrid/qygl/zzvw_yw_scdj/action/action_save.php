<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_scdj_action_save extends action_save{
	protected function fillDefaultValues($action, &$pair, $db, $table){
		parent::fillDefaultValues($action, $pair, $db, $table);
		if(!empty($this->params['zzvw_pici_scdj'])){
			foreach($this->params['zzvw_pici_scdj']['data'] as &$data){
				$data['happen_date'] = $this->params['happen_date'];
				$data['remained'] = $data['amount'];
				$data['hb_id'] = $this->params['hb_id'];
				$data['gx_id'] = $this->params['gx_id'];
				$data['yw_fl_id'] = $pair['yw_fl_id'];
			}
		}
		$res = $this->tool->query("SELECT name FROM hb WHERE id={$pair['hb_id']}");
		$hb = $res->fetch();
		
		$res = $this->tool->query("SELECT name FROM yw_fl WHERE id={$pair['yw_fl_id']}");
		$yw_fl = $res->fetch();
		$name = $hb['name'].'在'.$pair['happen_date'].$yw_fl['name'];
		$pair['name'] = $name;
// print_r($this->params);
	}
	
	protected function afterSave($affectID){ //需要更新所用的原料等，更新员工应收款信息
// print_r($this->params['gx_id'])		;
		parent::afterSave($affectID);
		//从工序定义表查找要消耗的物资
		$res = $this->db->query("SELECT * FROM gx WHERE id={$this->params['gx_id']}");
		$gx = $res->fetch();
// print_r($gx);		
		if($gx['gx_fl_id'] == GX_FL_FJ){ //分解，需要在此处理。其他类型的在zzvw_pici_scdj的actiong_save里处理
			$model = new Application_Model_Yw($this->params);
			//将被分解的产品出库
			$chuku = array('yw_id'=>$affectID, 'gx_id'=>$this->params['gx_id'], 'pici_id'=>$this->params['input_pici_id'], 'amount'=>$this->params['input_pici_amount']);
			$model->chuku($chuku, $this->params['input_pici_id'], YW_FL_SC);
			//如果分解出来的零件中有辅助性的，则将该辅助性产品直接转为材质对应的原料
			$res = $this->tool->query("select * from wz where id={$gx['wz_id']}"); //本工序的材质
			$caizhi = $res->fetch();
			
			$res = $this->tool->query("SELECT * FROM pici WHERE id={$this->params['input_pici_id']}");
			$row = $res->fetch();
			$wz_id = $row['wz_id'];
			$res = $this->tool->query("SELECT wz.id, wz.tj FROM wz_cp_zuhe left join wz on wz_cp_zuhe.input_wz_id=wz.id where wz_cp_zuhe.wz_id=$wz_id and wz.cp=2");
			while($row = $res->fetch()){
				$ruku = array('yw_id'=>$affectID, 'gx_id'=>$this->params['gx_id'], 'wz_id'=>$caizhi['wz_id'], 
					'amount'=>$caizhi['midu'] * $row['tj'] / 1000000,  //克-->吨
					'defetct_id'=>0, 'hb_id'=>$this->params['hb_id'], 'happen_date'=>$this->params['happen_date']);
				$model->ruku($ruku);
			}
		}
	}	
}

?>