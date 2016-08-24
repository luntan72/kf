<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//运送物资清单管理
class qygl_zzvw_dingdan_jfjh extends table_desc{
	protected function init($params){
		parent::init($params);
		// $this->params['yw_fl_id'] = 2;
// print_r($this->params);		
		$this->params['real_table'] = 'dingdan_jfjh';
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'yw_id'=>array('label'=>'业务'),
			'dingdan_hb_id'=>array('label'=>'供应商/客户', 'editable'=>true, 'editrules'=>array('required'=>true)),
			'dingdan_id'=>array('label'=>'物资订单', 'data_source_table'=>'zzvw_dingdan_executing', 'hidden'=>true),
			'dingdan_wz_id'=>array('label'=>'物资', 'disabled'=>true, 'data_source_table'=>'wz'),
			'pici_id'=>array('label'=>'批次'),
			'pici_defect_id'=>array('label'=>'缺陷', 'data_source_table'=>'defect'),
			'dingdan_amount'=>array('label'=>'订单数量', 'post'=>array('value'=>'?'), 'disabled'=>true),
			'dingdan_completed_amount'=>array('label'=>'订单已完成量', 'post'=>array('value'=>'?'), 'disabled'=>true),
			'dingdan_remained'=>array('label'=>'订单未完成量', 'post'=>array('value'=>'?'), 'DATA_TYPE'=>'float', 'editable'=>true, 'disabled'=>true),
			'pici_remained'=>array('label'=>'批次余量', 'post'=>array('value'=>'?')),
			'happen_amount'=>array('label'=>'发运数量', 'post'=>array('value'=>'?'), 'min'=>0.1),
			'unit_name'=>array('label'=>'单位'), 
			'happen_date'=>array('label'=>'生成日期', 'hidden'=>true),
			'created'=>array('label'=>'记录日期', 'editable'=>false),
        );
		switch($this->params['yw_fl_id']){
			case YW_FL_SH: //收货
				$this->options['edit'] = array(
					'dingdan_hb_id', 'dingdan_id', 'dingdan_wz_id', 'dingdan_amount', 'dingdan_completed_amount', 'dingdan_remained', 'pici_defect_id', 'happen_amount'
				);
				$this->options['list']['dingdan_hb_id']['label'] = '供应商';
				$this->options['list']['dingdan_hb_id']['data_source_table'] = 'zzvw_stgys';
				break;
			case YW_FL_TH://退货
				$this->options['edit'] = array(
					'dingdan_hb_id', 'dingdan_id', 'dingdan_wz_id',
					'pici_id', 'pici_remained'=>array('disabled'=>true), 'pici_defect_id',
					'happen_amount'=>array('label'=>'数量')
				);
				$this->options['list']['dingdan_hb_id']['label'] = '供应商';
				$this->options['list']['dingdan_hb_id']['data_source_table'] = 'zzvw_stgys';
				break;
			case YW_FL_FH: //发货
			case YW_FL_JTH://接退货
			default: 
				$this->options['edit'] = array(
					'dingdan_hb_id', 'dingdan_id', 
					'pici_id', 'pici_remained'=>array('disabled'=>true), 'pici_defect_id',
					'happen_amount'=>array('label'=>'数量')
				);
				$this->options['list']['dingdan_hb_id']['label'] = '客户';
				$this->options['list']['dingdan_hb_id']['data_source_table'] = 'zzvw_kh';
				break;
		}
	}
	
	protected function getButtons(){
        $buttons = parent::getButtons();
		unset($buttons['add']);
		return $buttons;
	}
}
