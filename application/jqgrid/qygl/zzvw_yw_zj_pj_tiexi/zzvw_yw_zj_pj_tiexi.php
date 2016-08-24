<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//资金管理

class qygl_zzvw_yw_zj_pj_tiexi extends qygl_yw{
	protected function init($params){
		parent::init($params);
		
		$this->options['edit']['yw_fl_id']['type'] = 'hidden';
		$this->options['edit']['yw_fl_id']['defval'] = YW_FL_PJTX;
		$this->options['add']['yw_fl_id']['type'] = 'hidden';
		$this->options['add']['yw_fl_id']['defval'] = YW_FL_PJTX;
// print_r($this->options['add']);
		$this->options['linkTables'] = array(
			'one2one'=>array(
				array('table'=>'yw_zj_pj_tiexi', 'self_link_field'=>'yw_id'),
			),
			'one2m'=>array(
				array('table'=>'zj_pj', 'self_link_field'=>'from_yw_id')
			)
		);
	}
	
	protected function getDetailListColumns(){
		return array(
			'zjzh_id'=>array('label'=>g_str('pj_zjzh'), 'data_source_table'=>'zzvw_zjzh_pj', 'from'=>'qygl.yw_zj_pj_tiexi', 'editable'=>true),
			'zj_pj_id'=>array('from'=>'qygl.yw_zj_pj_tiexi', 'editable'=>true, 'data_source_condition'=>array(array("field"=>"to_yw_id", 'op'=>'=', 'value'=>0))), 
			'total_money'=>array('label'=>g_str('pj_total_money'), 'post'=>array('value'=>'元'), 'disabled'=>true),
			'zj_pj'=>array('label'=>g_str('divided_bill'), 
				'formatter'=>'multi_row_edit', 'legend'=>'', 'data_source_table'=>'zj_pj',
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'编号:%(code)s, 金额:%(total_money)s元')
				),
			'cash_zjzh_id'=>array('data_source_table'=>'zzvw_zjzh_cash', 'from'=>'qygl.yw_zj_pj_tiexi'),
			'amount'=>array('label'=>g_str('total_money'), 'post'=>array('value'=>'元'), 'from'=>'qygl.yw_zj_pj_tiexi', 'editable'=>true),
			'cost'=>array('post'=>array('value'=>'元'), 'defval'=>0, 'from'=>'qygl.yw_zj_pj_tiexi', 'editable'=>true),
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
