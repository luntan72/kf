<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//发票
class qygl_fp extends table_desc{
	protected function init($params){
// print_r($params);
		parent::init($params);
		$this->options['list'] = array(
			'fp_fl_id'=>array('label'=>'发票类型'),
			'summary'=>array('label'=>'概述'),
			'hb_id'=>array('label'=>'客户/供应商', 'data_source_table'=>'zzvw_gys_kh'),
			'in_or_out'=>array('label'=>'采购/销售', 'formatoptions'=>array('value'=>array(1=>'销售', 2=>'采购')), 'formatter'=>'select', 'edittype'=>'select'),
			'yw_id'=>array('label'=>'来源于业务', 'hidden'=>true),
			'from_date'=>array('label'=>'业务起始日期'),
			'to_date'=>array('label'=>'业务结束日期'),
			'amount'=>array('label'=>'总金额', 'post'=>'元'),
			'code'=>array('label'=>'编号'),
			'cyr_id'=>array('label'=>'寄送人', 'data_source_table'=>'zzvw_cyr'),
			'yunfei'=>array('label'=>'寄送费用', 'post'=>'元'),
			'remained_amount'=>array('label'=>'未支付金额')
		);
		$this->options['caption'] = '发票';
		$this->options['displayField'] = 'summary';
	}
	
	protected function getButtonForList(){
		$buttons = parent::getButtonForList();
		unset($buttons['add']);
		return $buttons;
	}
	
	protected function getButtonForInfo(){
		return array();
	}	
}
