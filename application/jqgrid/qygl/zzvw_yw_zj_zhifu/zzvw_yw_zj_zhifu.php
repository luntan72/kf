<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//资金管理

class qygl_zzvw_yw_zj_zhifu extends qygl_yw{
	protected function init($params){
		parent::init($params);
		$this->options['list']['hb_id']['label'] = g_str('skf');//收款方';
		unset($this->options['list']['yw_fl_id']);
		unset($this->options['list']['account_receivable']);
		$this->options['edit']['yw_fl_id']['type'] = 'hidden';
		$this->options['edit']['yw_fl_id']['defval'] = YW_FL_ZHIFU;
		$this->options['add']['yw_fl_id']['type'] = 'hidden';
		$this->options['add']['yw_fl_id']['defval'] = YW_FL_ZHIFU;
		$this->options['linkTables'] = array(
			'one2one'=>array(
				'yw_zj_zhifu'=>array('table'=>'yw_zj_zhifu', 'self_link_field'=>'yw_id'),
				// array('table'=>'zj_pj', 'self_link_field'=>'to_yw_id')
			),
			'm2m'=>array('fp'=>array('link_table'=>'fp_yw', 'self_link_field'=>'yw_id', 'link_field'=>'fp_id'))
		);
		$this->options['caption'] = '支付';
		$this->options['displayField'] = 'name';
	}
	
	protected function getDetailListColumns(){
		return array(
			'account_receivable'=>array('post'=>'元', 'DATA_TYPE'=>'float', 'editable'=>true, 'disabled'=>true),
			'zj_cause_id'=>array('label'=>g_str('pay_cause'), 'data_source_table'=>'zzvw_zj_cause_zhifu', 'from'=>'qygl.yw_zj_zhifu', 'editable'=>true, 'editrules'=>array('required'=>true)),
			'zj_fl_id'=>array('editable'=>true, 'from'=>'qygl.yw_zj_zhifu', 'editrules'=>array('required'=>true)),
			'zjzh_id'=>array('label'=>g_str('pay_account'), 'data_source_table'=>'zjzh', 'from'=>'qygl.yw_zj_zhifu', 'editable'=>true),
			'zj_pj_id'=>array('from'=>'qygl.yw_zj_zhifu', 'editable'=>true, 'editrules'=>array('required'=>false), 
				'data_source_condition'=>array(array("field"=>"to_yw_id", 'op'=>'=', 'value'=>0))),
			// 'zj_pj_fl_id'=>array('label'=>'票据类型', 'from'=>'qygl.zj_pj', 'editable'=>true, 'disabled'=>true),
			// 'code'=>array('label'=>'票据编号', 'from'=>'qygl.zj_pj', 'editable'=>true, 'disabled'=>true),
			// 'total_money'=>array('label'=>'票据面额', 'from'=>'qygl.zj_pj', 'editable'=>true, 'disabled'=>true),
			'expire_date'=>array('DATA_TYPE'=>'date', 'from'=>'qygl.zj_pj', 'editable'=>true, 'disabled'=>true),
			'amount'=>array('label'=>g_str('total_money'), 'post'=>array('value'=>'元'), 'DATA_TYPE'=>'float', 'from'=>'qygl.yw_zj_zhifu', 'editable'=>true),
			'cost'=>array('post'=>array('value'=>'元'), 'defval'=>0, 'DATA_TYPE'=>'float', 'from'=>'qygl.yw_zj_zhifu', 'editable'=>true),
			'fp_id'=>array('data_source_table'=>'fp', 'type'=>'checkbox', 'cols'=>1, 'editable'=>true)
		);
	}
}
