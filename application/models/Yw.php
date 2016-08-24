<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));
require_once(APPLICATION_PATH.'/../library/const_def.php');
require_once(APPLICATION_PATH.'/../library/dbfactory.php');
require_once(APPLICATION_PATH.'/../library/kf_account.php');

class Application_Model_Yw extends kf_object{ //业务管理
	protected $account = null;
	private $last_pici_id = 0;
	
	protected function init($params){
		parent::init($params);
		$this->tool = toolFactory::get(array('tool'=>'db'));
		$this->tool->setDb('qygl');
		$this->account = new kf_account(array('db'=>'qygl', 'table'=>'hb', 'init_value_field'=>'init_account_receivable', 'current_value_field'=>'account_receivable', 'id'=>$params['hb_id']));
	}
	
	public function incYSK($hb_id, $amount){ //增加应收款
		$this->account->set(array('id'=>$hb_id));
		$this->account->inc($amount);
	}
	
	public function decYSK($hb_id, $amount){ //减少应收款
		$this->account->set(array('id'=>$hb_id));
		$this->account->dec($amount);
	}
	
	public function updateYSK($hb_id, $amount, $inc){ //更新应收款信息
		if($inc){
			$this->tool->query("UPDATE hb SET account_receivable=account_receivable + $amount where id=$hb_id");
		}
		else
			$this->tool->query("UPDATE hb SET account_receivable=account_receivable - $amount where id=$hb_id");
	}
	
	private function getGX_WZ_id($gx_id, $wz_id){
		$ret = 0;
		$res = $this->tool->query("SELECT id FROM gx_wz WHERE gx_id={$gx_id} and wz_id={$wz_id}");
		if($gx_wz = $res->fetch())
			$ret = $gx_wz['id'];
		else{
			$ret = $this->tool->insert('gx_wz', array('gx_id'=>$gx_id, 'wz_id'=>$wz_id));
		}
		return $ret;
		
	}
	
	public function getLastPici_id(){
		return $this->last_pici_id;
	}
	
	public function ruku($data, $yw_fl_id = YW_FL_SC){ //入库，生成批次信息，物资总量信息等
		$this->last_pici_id = 0;

// print_r($data);
// print_r($yw_fl_id);
		//data: array('yw_id', 'gx_id', 'wz_id', 'defect_id', 'amount', 'hb_id', 'happen_date', 'price');
		//首先生成批次信息
		$gx_wz_id = $this->getGX_WZ_id($data['gx_id'], $data['wz_id']);
		$res = $this->tool->query("SELECT name FROM hb WHERE id={$data['hb_id']}");
		$hb = $res->fetch();
		$res = $this->tool->query("SELECT name FROM gx WHERE id={$data['gx_id']}");
		$gx = $res->fetch();
		$res = $this->tool->query("SELECT name, unit_id FROM wz WHERE id={$data['wz_id']}");
		$wz = $res->fetch();
		$res = $this->tool->query("SELECT name FROM unit WHERE id={$wz['unit_id']}");
		$unit = $res->fetch();
		$pici = $data;//array('yw_id'=>$data['yw_id'], 'gx_id'=>$data['gx_id'], 'wz_id'=>)
		$pici['name'] = $hb['name'].'在'.$data['happen_date'].'入库, 工序:'.$gx['name'].', 物资:'.$wz['name'].', 数量:'.$data['amount'].$unit['name'];//-'.date('His').'-'.$hb_id.'-'.$gx_id.'-'.$wz_id;
		$pici['remained'] = $pici['amount'];
		//查找应存放的位置
		$res = $this->tool->query("SELECT * FROM defect_gx_wz WHERE gx_wz_id={$gx_wz_id} AND defect_id={$data['defect_id']}");
		if($r = $res->fetch())
			$pici['ck_weizhi_id'] = $r['ck_weizhi_id'];
		else
			$pici['ck_weizhi_id'] = 0;
// print_r($pici);
// print_r($data);
		$pici_id = $this->tool->insert('pici', $pici);
// print_r("pici_id = $pici_id\n");
		//再生成入库记录
		// $id = $this->tool->insert('ruku', $ruku);
		if(!empty($data['dingdan_id'])){
			$ruku = array('yw_id'=>$data['yw_id'], 'pici_id'=>$pici_id);
			$ruku['happen_date'] = $data['happen_date'];
			$ruku['dingdan_id'] = $data['dingdan_id'];
			$ruku['amount'] = $data['amount'];
			$ruku['defect_id'] = $data['defect_id'];
			$this->updateDingdan($ruku, $pici_id, $yw_fl_id);
		}
		//更新物资数量
		$this->incGxWzAmount($data['gx_id'], $data['wz_id'], $data['defect_id'], $data['amount']);
		
 // print_r("finished ruku, id = $id\n");	
		$this->last_pici_id = $pici_id;
		return 0;//$id;
	}
	
	public function chuku($data, $pici_id, $yw_fl_id){ //出库，修改批次信息，物资总量信息等. 
		// pici_id: -1: 用最早的， -2： 用最新的， 其他：用指定的批次
		//$data: array('yw_id', 'gx_id', 'wz_id', 'amount')
// print_r("出库中, 数据:");
// print_r($data);
		$id = 0;
		$remained = $data['amount'];
		//使用最早的批次，更新批次信息
		$gx_where = ' ';
		if(!empty($data['gx_id']))
			$gx_where = " AND gx_id in ({$data['gx_id']}) ";
// print_r($gx_where);		
		switch($pici_id){
			case CHUKU_STRATEGY_USE_OLDEST:
			case CHUKU_STRATEGY_USE_NEWEST:
				$sql = "SELECT * FROM pici WHERE wz_id={$data['wz_id']} $gx_where AND remained>0 ORDER BY happen_date ".
					(($pici_id == CHUKU_STRATEGY_USE_NEWEST) ? "DESC" : "ASC");
				break;
			default:
				$sql = "SELECT * FROM pici WHERE id=$pici_id and remained>0";
				break;
		}
// print_r($sql);		
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
// print_r($row);			
			if(empty($data['wz_id']))
				$data['wz_id'] = $row['wz_id'];
			if(empty($data['defect_id']))
				$data['defect_id'] = $row['defect_id'];
			
			$chuku_amount = $row['remained'];
			if($chuku_amount >= $remained)
				$chuku_amount = $remained;
			$remained -= $chuku_amount;
			$this->tool->query("UPDATE pici SET remained=remained-$chuku_amount WHERE id={$row['id']}");
			$chuku = array('yw_id'=>$data['yw_id'], 'pici_id'=>$row['id'], 'amount'=>$chuku_amount);
// print_r($chuku);			
			$id = $this->tool->insert('chuku', $chuku);
			//如果有dingdan_id，则添加订单交付计划记录
			if(!empty($data['dingdan_id'])){
				$chuku['happen_date'] = $data['happen_date'];
				$chuku['dingdan_id'] = $data['dingdan_id'];
				$chuku['defect_id'] = $data['defect_id'];
				$this->updateDingdan($chuku, $row['id'], $yw_fl_id);
			}
			if($remained <= 0)
				break;
		}
		//更新物资数量
		$chuku_sum = $data['amount'] - $remained;
		if($chuku_sum > 0)
			$this->decGxWzAmount($data['gx_id'], $data['wz_id'], $data['defect_id'], $chuku_sum);
		return $id;
	}
	
	public function incGxWzAmount($gx_id, $wz_id, $defect_id, $amount){
		return $this->updateGxWzAmount($gx_id, $wz_id, $defect_id, $amount, true);
	}
	
	public function decGxWzAmount($gx_id, $wz_id, $defect_id, $amount){
		return $this->updateGxWzAmount($gx_id, $wz_id, $defect_id, $amount, false);
	}
	
	private function updateGxWzAmount($gx_id, $wz_id, $defect_id, $amount, $inc){
		$gx_wz_id = $this->getGX_WZ_id($gx_id, $wz_id);
		// $cond = "gx_wz_id=$gx_wz_id AND defect_id=$defect_id";
		// $remained = "remained+$amount";
		// if($inc == false)
			// $remained = "remained-$amount";
		// // $c = $this->tool->query("UPDATE defect_gx_wz set remained=remained-$amount WHERE $cond");
		// $c = $this->tool->update('defect_gx_wz', array('remained'=>$remained), $cond);
// // print_r($c)		;
// print_r("c == $c, amount = $amount, reamined = $remained, cond = $cond")		;
		// if($c == 0){ //没找到
			// $id = $this->tool->insert('defect_gx_wz', array('gx_wz_id'=>$gx_wz_id, 'defect_id'=>$defect_id, 'remained'=>));
		// }
		$res = $this->tool->query("SELECT * FROM defect_gx_wz WHERE gx_wz_id=$gx_wz_id AND defect_id=$defect_id");
		if($r = $res->fetch()){
			$id = $r['id'];
			if($inc){
				$this->tool->query("UPDATE defect_gx_wz SET remained=remained+$amount WHERE id=$id");
			}
			else{
				$this->tool->query("UPDATE defect_gx_wz SET remained=remained-$amount WHERE id=$id");
			}
		}
		else{
			$id = $this->tool->insert('defect_gx_wz', array('gx_wz_id'=>$gx_wz_id, 'defect_id'=>$defect_id, 'remained'=>$inc ? $amount : -$amount));
		}
	}
	
	private function updateDingdan($data, $pici_id, $yw_fl_id){
// print_r($data);		
		// 更新采购执行计划
		$res = $this->tool->query("select * from dingdan_jfjh WHERE dingdan_id={$data['dingdan_id']} and happen_amount=0 order by plan_date ASC limit 1");
		if($jfjh = $res->fetch()){ //有未执行的交付计划
			$jfjh['happen_date'] = $data['happen_date'];
			$jfjh['happen_amount'] = $data['amount'];
			$jfjh['pici_id'] = $pici_id;
			$jfjh['jf_yw_id'] = $data['yw_id'];
			$this->tool->update('dingdan_jfjh', $jfjh);
		}
		else{ //没有未执行的交付计划
			$jfjh = array('yw_id'=>$data['yw_id'], 'pici_id'=>$pici_id, 'dingdan_id'=>$data['dingdan_id'], 'happen_date'=>$data['happen_date'], 'happen_amount'=>$data['amount']);
			$this->tool->insert('dingdan_jfjh', $jfjh);
		}
		//更新订单完成情况
		$res = $this->tool->query("select * from dingdan WHERE id={$data['dingdan_id']}");
		if($dingdan = $res->fetch()){
			if($yw_fl_id == YW_FL_SH || $yw_fl_id == YW_FL_FH) //收货或者发货
				$dingdan['completed_amount'] = $dingdan['completed_amount'] + $data['amount'];
			else //退货或接收退货
				$dingdan['completed_amount'] = $dingdan['completed_amount'] - $data['amount'];
			
			if($dingdan['completed_amount'] >= $dingdan['amount']) //如果完成数量大于等于订单量，则设置订单状态为已结束
				$dingdan['dingdan_status_id'] = DINGDAN_STATUS_JIESHU;
			$this->tool->update('dingdan', $dingdan);
		}
		else{
			
		}
		
		//更新供应商的应收款信息
		$res = $this->tool->query("SELECT hb_id FROM zzvw_dingdan WHERE id={$data['dingdan_id']}");
		$yw = $res->fetch();
// print_r($this->params);		
		$total_money = $data['amount'] * $dingdan['price'];
		$in = true;
		$out = false;
		if($yw_fl_id == YW_FL_SH || $yw_fl_id == YW_FL_JTH) //收到货物
			$this->decYSK($yw['hb_id'], $total_money);
		else //发出货物
			$this->incYSK($yw['hb_id'], $total_money);
	}
	
	protected function yunshu($data){ //运输
		
	}
	
	protected function zhuangxie($data){ //装卸
		
	}
	
	public function scdj($data){ //生产登记，调用入库
		
	}
	
	public function xd($data){ //下采购单
		
	}
	
	public function sh($data){ // 收货
		$ruku = array(
			'gx_id'=>GX_CG,
			'wz_id'=>$data['dingdan_wz_id'],
			'amount'=>$data['happen_amount'],
			'defect_id'=>$data['pici_defect_id'],
			'hb_id'=>$data['dingdan_hb_id'],
			'yw_id'=>$data['yw_id'],
			'dingdan_id'=>$data['dingdan_id'],
			'happen_date'=>$data['happen_date']
		);
		return $this->ruku($ruku, YW_FL_SH);
	}
/*	
Array
(
    [dingdan_id] => 14
    [pici_id] => 9
    [happen_amount] => 1
    [happen_date] => 2015-07-28
    [yw_id] => 58
    [created] => 2015-07-28 17:30:25	
)
*/
	public function th($data){ //退货
		$res = $this->tool->query("SELECT * FROM pici WHERE id={$data['pici_id']}");
		$row = $res->fetch();
		$data['gx_id'] = $row['gx_id'];
		$data['wz_id'] = $row['wz_id'];
		$data['defect_id'] = $row['defect_id'];
		$data['amount'] = $data['happen_amount'];
// print_r($data);	
		return $this->chuku($data, $data['pici_id'], YW_FL_TH);
	}
	
	public function jd($data){ //接单
		
		
	}
	
	public function fh($data){ //发货
		$res = $this->db->query("SELECT * FROM pici WHERE id={$data['pici_id']}");
		$row = $res->fetch();
		$data['gx_id'] = $row['gx_id'];
		$data['wz_id'] = $row['wz_id'];
		$data['defect_id'] = $row['defect_id'];
print_r($data);	
		return $this->chuku($data, $data['pici_id'], YW_FL_FH);
	}
	
	public function jth($data){ //接退货
		return $this->ruku($data, YW_FL_JTH);
	}
	
	public function zf($data){ //支付
		
	}
	
	public function hk($data){ //回款
		
	}
	 
	public function huabo($data){ //不同账户间划拨
		
	}
	
	public function tiexi_chaifen($data){ //承兑汇票贴息拆分
		
	}
	
	public function wx($data){ //设备维修
		
	}
}

