<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");

class qygl_zzvw_wz_action_get_wz_detail extends action_jqgrid{
	protected function handlePost(){
		if(empty($this->params['defect_id']))
			$this->params['defect_id'] = 1;
		
		$ret = array();
		$pre_gx = $this->params['pre_gx_id'];//array();
		$gx = array();
		$res = $this->db->query("SELECT * FROM gx WHERE id={$this->params['gx_id']}");
		$gx = $res->fetch();
		
		$wz = array();
		$res = $this->db->query("SELECT * FROM wz WHERE id={$this->params['wz_id']}");
		$wz = $res->fetch();
		// $res = $this->db->query("SELECT * FROM gx_pre_gx WHERE gx_id={$this->params['gx_id']}");
		// while($r_pre = $res->fetch()){
			// $pre_gx[] = $r_pre['pre_gx_id'];
		// }
		$sql = "SELECT price, ck_weizhi_id FROM defect_gx_wz left join gx_wz on defect_gx_wz.gx_wz_id=gx_wz.id ".
			"WHERE wz_id={$this->params['wz_id']} AND gx_id={$this->params['gx_id']} AND defect_id={$this->params['defect_id']}";
		$res = $this->tool->query($sql);
		$ret = $res->fetch();
		if(!empty($pre_gx)){
			$pre_gx_ids = $pre_gx; //implode(',', $pre_gx);
			if($gx['gx_fl_id'] == GX_FL_ZUHE){ //需要判断零配件的数量，根据零配件的数量决定最大的可能数量
				$available = 0;
				$sql = "SELECT sum(defect_gx_wz.remained) as total_available, wz_cp_zuhe.amount ".
					" from defect_gx_wz left join gx_wz on gx_wz.id=defect_gx_wz.gx_wz_id".
					" left join wz_cp_zuhe on wz_cp_zuhe.input_wz_id=gx_wz.wz_id".
					" WHERE wz_cp_zuhe.wz_id={$this->params['wz_id']} and gx_wz.gx_id in ($pre_gx_ids) and defect_id={$this->params['defect_id']}".
					" group by wz_cp_zuhe.input_wz_id";
// print_r($sql);					
				$res = $this->tool->query($sql);
				while($row = $res->fetch()){
// print_r($row);					
					$temp = floor($row['total_available']/ $row['amount']);
					if($available == 0 || $available > $temp)
						$available = $temp;
				}
				$ret['remained'] = $available;
			}
			elseif($gx['gx_fl_id'] == GX_FL_FJ){ //分解
// print_r($this->params);			
				switch($this->params['pici_id']){
					case CHUKU_STRATEGY_USE_OLDEST: //最先生产的先用
					case CHUKU_STRATEGY_USE_NEWEST: //最新生产的先用
						$sql = "SELECT wz_cp_zuhe.amount * sum(pici.remained) as total_available ".
							" from defect_gx_wz left join gx_wz on gx_wz.id=defect_gx_wz.gx_wz_id".
							" left join wz_cp_zuhe on wz_cp_zuhe.input_wz_id=pici.wz_id".
							" WHERE pici.wz_id={$this->params['wz_id']} and pici.gx_id={$this->params['gx_id']} and defect_id={$this->params['defect_id']}".
							" group by wz_cp_zuhe.input_wz_id";
						$res = $this->tool->query($sql);
						$row = $res->fetch();
						$ret['remained'] = $row['total_available'];
						break;
					default:
						if(!empty($this->params['pici_amount'])){
							$sql = "SELECT wz_id, remained from pici WHERE id={$this->params['pici_id']}";
							$res = $this->tool->query($sql);
							$row = $res->fetch();
	// print_r($row);
							$pici_remained = min($row['remained'], $this->params['pici_amount']);
							$wz_id = $row['wz_id'];
							
							$sql = "SELECT wz_cp_zuhe.amount ".
								" from wz_cp_zuhe".
								" WHERE wz_id=$wz_id and input_wz_id={$this->params['wz_id']}";
	// print_r($sql);							
							$res = $this->tool->query($sql);
							$row = $res->fetch();
	// print_r($row);
							$ret['remained'] = $pici_remained * $row['amount'];
						}
						else
							$ret['remained'] = 0;
						break;
						
				}
			}
			else{
				$sql = "SELECT sum(remained) as total_remained from defect_gx_wz left join gx_wz on gx_wz.id=defect_gx_wz.gx_wz_id ".
					" WHERE wz_id={$this->params['wz_id']} and gx_id in ($pre_gx_ids) and defect_id={$this->params['defect_id']}";
// print_r($sql);
				$res = $this->tool->query($sql);
				if($row = $res->fetch()){
					$ret['remained'] = $row['total_remained'];
				}
			}
		}
		else{ //没有前置工序,则需要根据工序类型判断所用的原料数量能生产多少产品
			$total_remained = 0;
			$res = $this->tool->query("SELECT gx_input.*, wz.midu, wz.unit_id FROM gx_input left join wz on gx_input.wz_id=wz.id where gx_id={$this->params['gx_id']}");
			while($gx_input = $res->fetch()){
				$sql = "SELECT sum(defect_gx_wz.remained) as total_available ".
					" from defect_gx_wz left join gx_wz on gx_wz.id=defect_gx_wz.gx_wz_id".
					" WHERE gx_wz.wz_id in ({$gx_input['wz_id']}) and defect_id={$gx_input['defect_id']}"; //得到需要的原料总量
				$res1 = $this->tool->query($sql);
				if($tmp = $res1->fetch()){
					$total_wz = $tmp['total_available'];
					if($total_wz > 0){
						switch($gx['gx_fl_id']){
							case GX_FL_ZH: //置换
							case GX_FL_TG: //涂裹
								$calc_method_id = $gx_input['calc_method_id'];
								$ratio = 1;
								switch($calc_method_id){
									case CALC_METHOD_GUDING: //固定值
										$ratio = 1;
										break;
									case CALC_METHOD_TJBL: //按体积比例
										$ratio = $wz['tj'] * $gx_input['midu'];
										break;
									case CALC_METHOD_BMJBL: //按表面积比例
										$ratio = $wz['bmj'];
										break;
								}
								$available = $total_wz * 1000000 / ($ratio * $gx_input['amount']); //吨转化为克
								if($total_remained == 0 || $total_remained > $available)
									$total_remained = $available;
								break;
						}
					}
				}
				$total_remained = floor($total_remained);
			}
			$ret['remained'] = $total_remained;
		}
		return $ret;
	}
}
