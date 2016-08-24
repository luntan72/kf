<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//下采购单
class qygl_zzvw_yw_jd extends qygl_yw{
	protected function init($params){
// print_r($params);
		parent::init($params);
		
		$this->options['list']['hb_id']['label'] = g_str('kh');
		$this->options['list']['hb_id']['data_source_table'] = 'zzvw_kh';
		$this->options['list']['yw_fl_id']['type'] = 'hidden';
		$this->options['list']['yw_fl_id']['defval'] = YW_FL_JD;
		$this->options['caption'] = g_str('kh').g_str('dingdan');
		$this->options['linkTables'] = array(
			'one2m'=>array(
				'zzvw_dingdan'=>array('table'=>'zzvw_dingdan', 'real_table'=>'dingdan', 'self_link_field'=>'yw_id')
				)
		);
		$this->options['parent'] = array('table'=>'zzvw_kh', 'field'=>'hb_id');
	}
	
	protected function getDetailListColumns(){
		return array(
			'zzvw_dingdan'=>array('label'=>g_str('order').g_str('detail_list'), 'editable'=>true, 'legend'=>'', 'editrules'=>array('required'=>true),
				'formatter'=>'multi_row_edit', 
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>"%(defect_id)s的%(wz_id)s %(amount)s, 单价%(price)s 元，已交付%(completed_amount)s")
				),
		);
	}
	
	protected function _setSubGrid(){
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'yw_id', 'db'=>'qygl', 'table'=>'dingdan');
	}
	
    // protected function getButtons(){
        // $buttons = array(
			// 'js'=>array('caption'=>'结束', 'title'=>'结束订单'),
			// 'jh'=>array('caption'=>'重新激活', 'title'=>'重新激活订单')
        // );
        // return array_merge($buttons, parent::getButtons());
    // }
}
