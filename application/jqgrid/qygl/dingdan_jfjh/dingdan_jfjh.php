<?php
require_once('table_desc.php');
//采购订单交付计划及执行情况记录
class qygl_dingdan_jfjh extends table_desc{
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'dingdan_id'=>array('label'=>'业务', 'hidden'=>true, 'hidedlg'=>true),
			'plan_date'=>array('label'=>'计划日期'),
			'plan_amount'=>array('label'=>'计划数量'),
			'happen_date'=>array('label'=>'实际日期'),
            'happen_amount'=>array('label'=>'实际数量'),
			'jf_yw_id'=>array('label'=>'运输', 'data_source_table'=>'yw'),
			'pici_id'=>array('label'=>'批次'),
            'note'=>array('label'=>'备注'),
        );
		$this->options['add'] = array('dingdan_id'=>array('type'=>'hidden'), 'plan_date', 'plan_amount');
		$this->options['edit'] = array('dingdan_id'=>array('type'=>'hidden'), 'plan_date', 'plan_amount', 'note');
		$this->options['parent'] = array('table'=>'dingdan', 'field'=>'dingdan_id');
		// $this->options['displayField'] = 'plan_date';
	}
}
