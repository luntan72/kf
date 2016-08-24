<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金管理

class qygl_zzvw_yw_zj_huabo extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'yw';
		$year_month2 = $this->tool->getYearMonthList(0, 36, true);
		$this->options['list'] = array(
			'out_zjzh_id'=>array('data_source_table'=>'zzvw_zjzh_cash', 'from'=>'qygl.yw_zj_huabo', 'editable'=>true),
			'in_zjzh_id'=>array('data_source_table'=>'zzvw_zjzh_cash', 'from'=>'qygl.yw_zj_huabo', 'editable'=>true),
			'amount'=>array('label'=>g_str('huabo_total_money'), 'post'=>array('value'=>'元'), 'from'=>'qygl.yw_zj_huabo', 'editable'=>true),
			'cost'=>array('post'=>array('value'=>'元'), 'defval'=>0, 'from'=>'qygl.yw_zj_huabo', 'editable'=>true),
			'dj'=>array(),
			'note'=>array(),
			'jbr_id'=>array('data_source_db'=>'qygl', 'data_source_table'=>'zzvw_yg'),
			'happen_date'=>array('edittype'=>'date',
				'stype'=>'select', 'searchoptions'=>array('value'=>$year_month2)), //只提供三年内的查询
			'*'=>array('hidden'=>true),
			
		);
		$this->options['edit'] = array('yw_fl_id'=>array('type'=>'hidden', 'defval'=>YW_FL_ZJHB),
			'hb_id'=>array('type'=>'hidden', 'defval'=>0), 
			'out_zjzh_id', 'out_zjzh_remained'=>array('DATA_TYPE'=>'float', 'editable'=>true, 'disabled'=>true, 'post'=>'元'),
			'in_zjzh_id', 'in_zjzh_remained'=>array('DATA_TYPE'=>'float', 'editable'=>true, 'disabled'=>true, 'post'=>'元'),
			'amount', 'cost',
			'dj', 'note', 'jbr_id', 'happen_date');
		$this->options['linkTables'] = array(
			'one2one'=>array(
				array('table'=>'yw_zj_huabo', 'self_link_field'=>'yw_id'),
			),
		);
		$this->options['displayField'] = 'id';
		$this->options['caption'] = '账户间资金划拨';
	}
}
