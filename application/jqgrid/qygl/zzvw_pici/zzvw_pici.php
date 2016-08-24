<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//收货批次管理
class qygl_zzvw_pici extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'pici';
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>'概述', 'editrules'=>array('required'=>true)),
			'yw_fl_id'=>array('label'=>'业务分类'),
			'yw_id'=>array('label'=>'业务', 'editable'=>false, 'hidden'=>true),
			'gx_id'=>array('label'=>'工序'),
			'wz_id'=>array('label'=>'物资'),
			'hb_id'=>array('label'=>'合作伙伴', 'editable'=>true, 'editrules'=>array('required'=>true)),
			'dingdan_id'=>array('label'=>'物资订单', 'data_source_table'=>'dingdan', 'hidden'=>true),
			
			'defect_id'=>array('label'=>'缺陷'),
			'amount'=>array('label'=>'原始总数量', 'post'=>array('value'=>'?')),
			'ck_weizhi_id'=>array('label'=>'存放位置'),
			'remained'=>array('label'=>'当前剩余量'),
			'happen_date'=>array('label'=>'生成日期', 'hidden'=>true),
			'created'=>array('label'=>'记录日期', 'editable'=>false),
        );
		
		$this->options['edit'] = array(
			'hb_id', 'dingdan_id', 'wz_id'=>array('disabled'=>true), 'defect_id', 'amount'=>array('label'=>'数量', 'title'=>'输入负数为退货'), 'ck_weizhi_id'
		);
	}
	
	protected function getButtons(){
        $buttons = parent::getButtons();
		unset($buttons['add']);
		return $buttons;
	}
}
