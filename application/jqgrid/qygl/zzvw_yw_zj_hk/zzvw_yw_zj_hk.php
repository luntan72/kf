<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw_tool.php");
require_once(APPLICATION_PATH."/jqgrid/qygl/hb_tool.php");
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");

//资金管理

class qygl_zzvw_yw_zj_hk extends qygl_yw{
	protected function init($params){
		parent::init($params);
		$this->options['list']['hb_id']['label'] = '付款方';
		unset($this->options['list']['yw_fl_id']);
		unset($this->options['list']['account_receivable']);
		$this->options['edit']['yw_fl_id']['type'] = 'hidden';
		$this->options['edit']['yw_fl_id']['defval'] = YW_FL_HK;
		$this->options['add']['yw_fl_id']['type'] = 'hidden';
		$this->options['add']['yw_fl_id']['defval'] = YW_FL_HK;
		$this->options['linkTables'] = array(
			'one2one'=>array(
				array('table'=>'yw_zj_hk', 'self_link_field'=>'yw_id'),
				// 'zj_pj'=>array('table'=>'zj_pj', 'self_link_field'=>'from_yw_id')
			),
		);
	}

	protected function getDetailListColumns(){
		return array(
			'account_receivable'=>array('label'=>'应收款', 'post'=>'元', 'DATA_TYPE'=>'float', 'editable'=>true, 'disabled'=>true),
			'zj_cause_id'=>array('label'=>'回款原因', 'data_source_table'=>'zzvw_zj_cause_hk', 'from'=>'qygl.yw_zj_hk', 'editable'=>true, 'editrules'=>array('required'=>true)),
			'zj_fl_id'=>array('label'=>'资金类型', 'from'=>'qygl.yw_zj_hk', 'editable'=>true),
			'zjzh_id'=>array('label'=>'进入账户', 'data_source_table'=>'zjzh', 'from'=>'qygl.yw_zj_hk', 'editable'=>true),
			'code'=>array('label'=>'票据编号', 'editable'=>true),
			'expire_date'=>array('label'=>'到期日', 'DATA_TYPE'=>'date', 'editable'=>true),
			'amount'=>array('label'=>'总金额', 'post'=>array('value'=>'元'), 'from'=>'qygl.yw_zj_hk', 'editable'=>true),
			'cost'=>array('label'=>'额外费用', 'post'=>array('value'=>'元'), 'defval'=>0, 'from'=>'qygl.yw_zj_hk', 'editable'=>true),
		);
	}
	
	// public function fillOptions(&$columnDef, $db, $table){
		// $hb_tool = new hb_tool($this->tool);
		// if($columnDef['name'] == 'out_zjzh_id' || $columnDef['name'] == 'in_zjzh_id'){
			// $o = array(0=>'');
			// $res = $this->tool->query("SELECT * FROM zjzh");
			// while($row = $res->fetch()){
				// $row['name'] .= " [账户余额{$row['remained']}元]";
				// $o[$row['id']] = $row;
			// }
			// $columnDef['editoptions']['value'] = $o;
		// }
		// elseif($columnDef['name'] == 'zj_pj_id'){
			// $o = array(0=>'');
			// $res = $this->tool->query("select * from zj_pj WHERE to_yw_id=0");
			// while($row = $res->fetch()){
				// $row['name'] = $row['code']." [总金额{$row['total_money']}元]";
				// $o[$row['id']] = $row;
			// }
			// $columnDef['editoptions']['value'] = $o;
		// }
		// else{
			// parent::fillOptions($columnDef, $db, $table);
		// }
	// }
}
