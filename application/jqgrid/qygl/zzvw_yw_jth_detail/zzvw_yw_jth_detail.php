<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//发货清单管理
class qygl_zzvw_yw_jth_detail extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'yw_sh_detail';
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'yw_id'=>array('label'=>'业务'),
			'hb_id'=>array('label'=>'客户', 'editable'=>true, 'editrules'=>array('required'=>true), 'data_source_table'=>'zzvw_kh'),
			'dingdan_id'=>array('label'=>'物资订单', 'data_source_table'=>'dingdan', 'hidden'=>true),
			'defect_id'=>array('label'=>'主要缺陷'),
			'wz_id'=>array('label'=>'物资', 'editable'=>true),
			'dingdan_amount'=>array('label'=>'订单数量', 'editable'=>true, 'post'=>array('value'=>'?')),
			'dingdan_remained'=>array('label'=>'订单未完成量', 'editable'=>true, 'post'=>array('value'=>'?')),
			'amount'=>array('label'=>'收到数量', 'post'=>array('value'=>'?'), 'min'=>0),
			'happen_date'=>array('label'=>'生成日期', 'hidden'=>true),
			'created'=>array('label'=>'记录日期', 'editable'=>false),
        );
		
		$this->options['edit'] = array(
			'hb_id', 'dingdan_id', 
			'wz_id'=>array('disabled'=>true), 
			'defect_id',
			'dingdan_amount'=>array('disabled'=>true), 
			'dingdan_remained'=>array('disabled'=>true), 
			'amount'
		);
	}
	
	protected function getButtons(){
        $buttons = parent::getButtons();
		unset($buttons['add']);
		return $buttons;
	}
}
