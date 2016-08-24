<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_zzvw_pici_scdj_action_save extends action_save{
	private $model = null;
	
	protected function prepare($db, $table, $pair){
// print_r($pair);
		$res = $this->tool->query("SELECT name FROM hb WHERE id={$pair['hb_id']}");
		$hb = $res->fetch();
		$res = $this->tool->query("SELECT name FROM gx WHERE id={$pair['gx_id']}");
		$gx = $res->fetch();
		$res = $this->tool->query("SELECT name, unit_name FROM zzvw_wz WHERE id={$pair['wz_id']}");
		$wz = $res->fetch();
		$res = $this->tool->query("SELECT name FROM defect WHERE id={$pair['defect_id']}");
		$defect = $res->fetch();
		
		$name = $pair['happen_date'].','.$hb['name'].'在'.$gx['name'].'生产了'.$pair['amount'].$wz['unit_name'].$defect['name'].'的'.$wz['name'];
		$pair['name'] = $name;
		
		$pair = parent::prepare($db, $table, $pair);
		return $pair;
	}

/*	
Array
(
    [db] => qygl
    [table] => zzvw_pici_scdj
    [wz_id] => 8
    [defect_id] => 1
    [price] => 0.2
    [amount] => 1234
    [ck_weizhi_id] => 1
    [happen_date] => 2015-06-26
    [remained] => 1234
    [hb_id] => 2
    [gx_id] => 2
    [yw_id] => 170
    [real_table] => pici
)	*/
	
	protected function newRecord($db, $table, $pair){
// print_r($this->params);
		$this->model = new Application_Model_Yw($this->params);
		$ruku_id = $this->model->ruku($pair);
		return $this->model->getLastPici_id();
	}
	
	protected function afterSave($affectID){ //需要更新所用的原料等，更新员工应收款信息
		$this->model = new Application_Model_Yw($this->params);
		$item = $this->params;
		$new_account_receivable = $item['price'] * $item['amount'];
		//更新所用原料信息，假定是用最早批次的原料
		$cp_id = $item['wz_id']; //产品
		$gx_id = $item['gx_id'];
		$amount = $item['amount'];
		//产品信息
		$res = $this->db->query("SELECT * FROM wz WHERE id=$cp_id");
		$cp = $res->fetch();
		//从工序定义表查找要消耗的物资
		$res = $this->db->query("SELECT * FROM gx WHERE id=$gx_id");
		$gx = $res->fetch();
		$pre_gx = array();
		$res = $this->db->query("SELECT * FROM gx_pre_gx WHERE gx_id=$gx_id");
		while($r_pre = $res->fetch()){
			$pre_gx[] = $r_pre['pre_gx_id'];
		}
		$gx['pre_gx_ids'] = implode(',', $pre_gx);
// print_r($gx);			
		switch($gx['gx_fl_id']){
			case GX_FL_ZUHE: //组合
				//先得到产品的组合信息
				$res = $this->db->query("SELECT * FROM wz_cp_zuhe WHERE wz_id=$cp_id");
				while($row = $res->fetch()){
					//应检查零件数量是否足够
					$chuku = array('yw_id'=>$this->params['yw_id'], 'gx_id'=>$gx['pre_gx_ids'], 'wz_id'=>$row['input_wz_id'], 'defect_id'=>1, 'amount'=>$row['amount'] * $item['amount']);
					$this->model->chuku($chuku, CHUKU_STRATEGY_USE_OLDEST, YW_FL_SC);
				}
				break;
			case GX_FL_TG: //涂裹
			case GX_FL_ZH: //置换
			case GX_FL_JG: //加工
// print_r("gx_fl_id = ".$gx['gx_fl_id']);
				$res = $this->db->query("SELECT gx_output.*, wz.midu FROM gx_output left join wz on gx_output.wz_id=wz.id WHERE gx_id=$gx_id AND defect_id=1");//{$gx['defect_id']}"); //产生的
				while($gx_output = $res->fetch()){
					switch($gx_output['calc_method_id']){
						case CALC_METHOD_GUDING: //固定值
							$replaced_wz_amount = $gx_output['amount'];
							break;
						case CALC_METHOD_TJBL: //体积比例
							$replaced_wz_amount = $amount * $cp['tj'] * $gx_output['amount'] * $gx_output['midu'] / 1000000.0; //克-->吨
							break;
						case CALC_METHOD_BMJBL: //表面积比例
							$replaced_wz_amount = $amount * $cp['bmj'] * $gx_output['amount'] * $gx_output['midu'] / 1000000.0; //克-->吨
							break;
					}
					//将置换出的材料入库
					if($replaced_wz_amount > 0){
						$ruku = array('yw_id'=>$this->params['yw_id'], 'gx_id'=>$gx_id, 'amount'=>$replaced_wz_amount, 'defetct_id'=>0, 'hb_id'=>$item['hb_id'], 'happen_date'=>$item['happen_date']);
// print_r($ruku);
						$this->model->ruku($ruku);
					}
				}
// print_r("消耗材料准备出库");
				$res = $this->db->query("SELECT gx_input.*, wz.midu FROM gx_input left join wz on gx_input.wz_id=wz.id WHERE gx_id=$gx_id AND defect_id=1");//{$gx['defect_id']}"); //输入的，也就是消耗的
				while($gx_input = $res->fetch()){
					switch($gx_input['calc_method_id']){
						case CALC_METHOD_GUDING: //固定值
							$wz_amount = $gx_input['amount'];
							break;
						case CALC_METHOD_TJBL: //体积比例
							$wz_amount = $amount * $cp['tj'] * $gx_input['amount'] * $gx_input['midu'] / 1000000.0;
							break;
						case CALC_METHOD_BMJBL: //表面积比例
							$wz_amount = $amount * $cp['bmj'] * $gx_input['amount'] * $gx_input['midu'] / 1000000.0;
							break;
					}
// print_r($gx_input);	
// print_r($wz_amount);		
					if($wz_amount > 0){
						$chuku = array('yw_id'=>$this->params['yw_id'], 'gx_id'=>$gx_input['from_gx_id'], 'defect_id'=>1, 'wz_id'=>$gx_input['wz_id'], 'amount'=>$wz_amount);
// print_r("chuku:");
// print_r($chuku);						
						$this->model->chuku($chuku, CHUKU_STRATEGY_USE_OLDEST, YW_FL_SC);
					}
				}
// print_r("出库完成");
				if(in_array($gx['gx_fl_id'], array(GX_FL_JG, GX_FL_TG, GX_FL_ZH)) && !empty($this->params['pre_gx_id'])){ //加工或涂裹或置换，应将上一工序的数量相应减少
					$chuku = array('yw_id'=>$this->params['yw_id'], 'gx_id'=>$this->params['pre_gx_id'], 'defect_id'=>1/*$gx['defect_id']*/, 'wz_id'=>$cp_id, 'amount'=>$amount);
// print_r($chuku);
					$this->model->chuku($chuku, CHUKU_STRATEGY_USE_OLDEST, YW_FL_SC);
				}
				break;
			case GX_FL_FJ: //分解，这个很麻烦，主要是需要清楚输入，否则不知道怎样处理，所以如果是分解工序，则在界面上必须有输入的信息
				//已经在上一层处理
				break;
			default:
				if(!empty($gx['pre_gx_ids'])){ //加工或涂裹或置换，应将上一工序的数量相应减少
					$chuku = array('yw_id'=>$this->params['yw_id'], 'gx_id'=>$this->params['pre_gx_id'], 'defect_id'=>$gx['defect_id'], 'wz_id'=>$cp_id, 'amount'=>$amount);
// print_r($chuku);
					$this->model->chuku($chuku, CHUKU_STRATEGY_USE_OLDEST, YW_FL_SC);
				}
				break;
		}
		//更新员工的应收款信息
	// print_r("更新员工应收款");
		$this->model->decYSK($this->params['hb_id'], $new_account_receivable);
		
		// return $ret;
	}
		
}

?>