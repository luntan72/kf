<?php 
require_once(APPLICATION_PATH.'/jqgrid/qygl/yw/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_yw_zj_zhifu_action_save extends qygl_yw_action_save{
	protected function generateName($pair, $hb, $yw_fl){
		$res = $this->tool->query("SELECT * FROM zj_cause WHERE id={$this->params['zj_cause_id']}");
		$zj_cause = $res->fetch();
		$name = $pair['happen_date'].'因'.$zj_cause['name'].'支付给'.$hb['name'].$this->params['amount'].'元';
		return $name;
	}
	
	protected function afterSave($affectID){
		$ret = parent::afterSave($affectID);
		//更新交易人的应收款信息以及账户余额
		$model = new Application_Model_Yw($this->params);
		$model->incYSK($this->params['hb_id'], $this->params['amount']);
		
		//更新账户余额
		$sql = "UPDATE zjzh set remained=remained-{$this->params['amount']} WHERE id={$this->params['zjzh_id']}";
// print_r($sql);		
		$this->tool->query($sql);
		
		//处理zj_pj表
		if($this->params['zj_fl_id'] != ZJ_FL_XIANJIN){
			$this->tool->update('zj_pj', array('to_yw_id'=>$affectID), 'id='.$this->params['zj_pj_id']);
		}
		
		return $ret;
	}
	
	protected function saveM2M($affectedID, $linkInfo){
		if(!empty($this->params['fp_id'])){
			$this->tool->setDb('qygl');
			$amount = $this->params['amount'];
			foreach($this->params['fp_id'] as $fp_id){
				$res = $this->tool->query("select * from fp where id=$fp_id");
				$row = $res->fetch();
				$remained = $row['amount'] - $row['paid_amount'];
				if($amount > $remained){
					$this->tool->insert('fp_yw', array('fp_id'=>$fp_id, 'yw_id'=>$affectedID, 'amount'=>$remained));
					$this->tool->update('fp', array('paid_amount'=>$row['amount']), "id=".$row['id']);
					$amount -= $remained;
				}
				else{
					$this->tool->insert('fp_yw', array('fp_id'=>$fp_id, 'yw_id'=>$affectedID, 'amount'=>$amount));
					$this->tool->update('fp', array('paid_amount'=>$row['paid_amount'] + $amount), "id=".$row['id']);
					break;
				}
			}
		}
	}
}

?>