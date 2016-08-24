<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//发货清单管理
class qygl_zzvw_yw_th_detail extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'yw_th_detail';
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'yw_id'=>array('label'=>'业务'),
			'hb_id'=>array('label'=>'供应商', 'editable'=>true, 'editrules'=>array('required'=>true), 'data_source_table'=>'zzvw_stgys'),
			'dingdan_id'=>array('label'=>'物资订单', 'data_source_table'=>'zzvw_dingdan_executing', 'hidden'=>true),
			'wz_id'=>array('label'=>'物资'),
			'pici_id'=>array('label'=>'批次'),
			'dingdan_amount'=>array('label'=>'订单数量', 'post'=>array('value'=>'?')),
			'dingdan_remained'=>array('label'=>'订单未完成量', 'post'=>array('value'=>'?')),
			'pici_remained'=>array('label'=>'批次余量', 'post'=>array('value'=>'?')),
			'amount'=>array('label'=>'发运数量', 'post'=>array('value'=>'?'), 'min'=>0),
			'happen_date'=>array('label'=>'生成日期', 'hidden'=>true),
			'created'=>array('label'=>'记录日期', 'editable'=>false),
        );
		
		$this->options['edit'] = array(
			'hb_id', 'dingdan_id', 'wz_id'=>array('disabled'=>true), 'pici_id', 
			'dingdan_amount'=>array('disabled'=>true), 'dingdan_remained'=>array('disabled'=>true), 
			'pici_remained'=>array('disabled'=>true), 
			'amount'=>array('label'=>'数量')
		);
	}
	
	protected function getButtons(){
        $buttons = parent::getButtons();
		unset($buttons['add']);
		return $buttons;
	}
}
