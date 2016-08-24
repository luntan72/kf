<?php
require_once('exporter_excel.php');
/*
需要包含整体情况，生产情况，发货情况，采购情况，财务收支情况，财务当前状态，应收应付状态，客户订单情况

*/
class qygl_tj_exporter_qygl_tj extends exporter_excel{
	public function setOptions($jqgrid_action){
		$headers = array(
			'summary'=>array( //整体情况
				array('index'=>'type_id', 'width'=>100, 'label'=>'项目', 'cols'=>1),
				array('index'=>'from_date', 'width'=>100, 'label'=>'从'.$this->params['from_date'], 'cols'=>1),
				array('index'=>'end_date', 'width'=>200, 'label'=>'到'.$this->params['end_date'], 'cols'=>1),
				array('index'=>'content', 'width'=>100, 'label'=>'内容', 'cols'=>1),
				array('index'=>'note', 'width'=>100, 'label'=>'备注', 'cols'=>1),
			),
			'scqk'=>array( //生产情况
				array('index'=>'gx_id', 'width'=>100, 'label'=>'工序', 'cols'=>1),
				array('index'=>'hb_id', 'width'=>100, 'label'=>'员工', 'cols'=>1),
				array('index'=>'wz_id', 'width'=>200, 'label'=>'产品', 'cols'=>1),
				array('index'=>'defect_id', 'width'=>100, 'label'=>'质量', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'数量', 'cols'=>1),
			),
			'fhqk'=>array( //发货情况
				array('index'=>'hb_id', 'width'=>100, 'label'=>'客户', 'cols'=>1),
				array('index'=>'wz_id', 'width'=>200, 'label'=>'产品', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'数量', 'cols'=>1),
			),
			'jthqk'=>array( //接收退货情况
				array('index'=>'hb_id', 'width'=>100, 'label'=>'客户', 'cols'=>1),
				array('index'=>'wz_id', 'width'=>200, 'label'=>'产品', 'cols'=>1),
				array('index'=>'cause', 'width'=>100, 'label'=>'退货原因', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'数量', 'cols'=>1),
			),
			'cgqk'=>array( //采购情况
				array('index'=>'hb_id', 'width'=>100, 'label'=>'供应商', 'cols'=>1),
				array('index'=>'wz_id', 'width'=>200, 'label'=>'产品', 'cols'=>1),
				array('index'=>'cause', 'width'=>100, 'label'=>'退货原因', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'数量', 'cols'=>1),
			),
			'thqk'=>array( //退货情况
				array('index'=>'hb_id', 'width'=>100, 'label'=>'供应商', 'cols'=>1),
				array('index'=>'wz_id', 'width'=>200, 'label'=>'产品', 'cols'=>1),
				array('index'=>'cause', 'width'=>100, 'label'=>'退货原因', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'数量', 'cols'=>1),
			), 
			'szqk'=>array( //收支情况
				array('index'=>'yw_fx_id', 'width'=>100, 'label'=>'类别', 'cols'=>1),
				array('index'=>'hb_id', 'width'=>200, 'label'=>'收（付）款方', 'cols'=>1),
				array('index'=>'happen_date', 'width'=>100, 'label'=>'日期', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'金额', 'cols'=>1),
				array('index'=>'zj_fl_id', 'width'=>100, 'label'=>'资金类型', 'cols'=>1),
				array('index'=>'code', 'width'=>100, 'label'=>'票据编号', 'cols'=>1),
				array('index'=>'zj_cuase_id', 'width'=>100, 'label'=>'原因', 'cols'=>1),
			),
			'jdqk'=>array( //新增订单
				array('index'=>'hb_id', 'width'=>200, 'label'=>'收（付）款方', 'cols'=>1),
				array('index'=>'happen_date', 'width'=>100, 'label'=>'日期', 'cols'=>1),
				array('index'=>'wz_id', 'width'=>100, 'label'=>'产品', 'cols'=>1),
				array('index'=>'amount', 'width'=>100, 'label'=>'数量', 'cols'=>1),
				array('index'=>'price', 'width'=>100, 'label'=>'单价', 'cols'=>1),
				array('index'=>'total_money', 'width'=>100, 'label'=>'总金额', 'cols'=>1),
			),
		);
		$data = $this->getData(null);
		$this->params['sheets'] = array(
			0=>array('title'=>'整体情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['summary'])), 'data'=>$data['summary']),
			1=>array('title'=>'生产情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['scqk'])), 'data'=>$data['scqk']),
			2=>array('title'=>'发货情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['fhqk'])), 'data'=>$data['fhqk']),
			2=>array('title'=>'接退货情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['jthqk'])), 'data'=>$data['jthqk']),
			2=>array('title'=>'采购情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['cgqk'])), 'data'=>$data['cgqk']),
			3=>array('title'=>'退货情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['thqk'])), 'data'=>$data['thqk']),
			4=>array('title'=>'新增订单', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['jdqk'])), 'data'=>$data['jdqk']),
			5=>array('title'=>'收支情况', 'startRow'=>2, 'startCol'=>1, 'header'=>array('rows'=>array($headers['szqk'])), 'data'=>$data['szqk']),
		);
		// $this->params['sheets'][0]['groups'] = array(array('index'=>'module'));		
	}

	protected function getData($table_desc, $searchConditions = array(), $order = array()){
		$ret = array(
			'summary'=>array(),
			'scqk'=>array(),
			'fhqk'=>array(),
			'jthqk'=>array(),
			'cgqk'=>array(),
			'thqk'=>array(),
			'jdqk'=>array(),
			'szqk'=>array(),
			
			);
		$db = dbFactory::get($this->params['db']);
		$from_date = $this->params['from_date'];
		$end_date = $this->params['end_date'];
		
		$this->tool->setDb($this->params['db']);
		$sql = "SELECT gx_id, hb_id, wz_id, defect_id, sum(amount) as amount from zzvw_pici_scdj where happen_date>=:from_date and happen_date<=:end_date ".
			" group by gx_id, hb_id, wz_id, defect_id order by gx_id, hb_id, wz_id, defect_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['scqk'][] = $row;
		}
		
		$sql = "SELECT detail.hb_id, wz_id, sum(amount) as amount from zzvw_yw_fh_detail detail left join yw on detail.yw_id=yw.id where happen_date>=:from_date and happen_date<=:end_date ".
			" group by hb_id, wz_id order by hb_id, wz_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['fhqk'][] = $row;
		}
		
		$sql = "SELECT detail.hb_id, wz_id, sum(amount) as amount from zzvw_yw_jth_detail detail left join yw on detail.yw_id=yw.id where happen_date>=:from_date and happen_date<=:end_date ".
			" group by hb_id, wz_id order by hb_id, wz_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['jthqk'][] = $row;
		}

		$sql = "SELECT detail.hb_id, wz_id, sum(amount) as amount from zzvw_yw_sh_detail detail left join yw on detail.yw_id=yw.id where happen_date>=:from_date and happen_date<=:end_date ".
			" group by hb_id, wz_id order by hb_id, wz_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['cgqk'][] = $row;
		}

		$sql = "SELECT detail.hb_id, wz_id, sum(amount) as amount from zzvw_yw_th_detail detail left join yw on detail.yw_id=yw.id where happen_date>=:from_date and happen_date<=:end_date ".
			" group by hb_id, wz_id order by hb_id, wz_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['thqk'][] = $row;
		}

		$sql = "SELECT zzvw_dingdan.hb_id, wz_id, amount, price, amount*price as total_money from zzvw_dingdan left join yw on zzvw_dingdan.yw_id=yw.id ".
			" where zzvw_dingdan.yw_fl_id=".YW_FL_JD." and happen_date>=:from_date and happen_date<=:end_date ".
			" group by hb_id, wz_id order by hb_id, wz_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['jdqk'][] = $row;
		}

		return $ret;
		$sql = "SELECT hb_id, wz_id, amount, price, amount*price as total_money from zzvw_dingdan left join yw on zzvw_dingdan.yw_id=yw.id ".
			" where yw_fl_id=".YW_FL_JD." and happen_date>=:from_date and happen_date<=:end_date ".
			" group by hb_id, wz_id order by hb_id, wz_id";
		$res = $this->tool->query($sql, array('from_date'=>$this->params['from_date'], 'end_date'=>$this->params['end_date']));
		while($row = $res->fetch()){
			$ret['szqk'][] = $row;
		}

		
		return $ret;
	}
	
	// protected function save(){
		// $fileName = parent::save();
		// print_r($fileName);
		// return $fileName;
	// }
};

?>
