<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw_tool.php");
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//下采购单
class qygl_zzvw_yw_xd extends qygl_yw{
	protected function init($params){
// print_r($params);
		parent::init($params);
		$this->options['list']['hb_id']['label'] = g_str('gys');
		$this->options['list']['hb_id']['data_source_table'] = 'zzvw_stgys';
		$this->options['list']['yw_fl_id']['type'] = 'hidden';
		$this->options['list']['yw_fl_id']['defval'] = YW_FL_XD;
		$this->options['caption'] = g_str('order');
		$this->options['linkTables'] = array(
			'one2m'=>array(
				'zzvw_dingdan'=>array('table'=>'zzvw_dingdan', 'real_table'=>'dingdan', 'self_link_field'=>'yw_id')
				)
		);

		// $this->options['add'] = array('yw_fl_id'=>array('type'=>'hidden', 'defval'=>YW_FL_XD), 'hb_id', 'zzvw_dingdan', 
			// 'note', 'jbr_id', 'happen_date');
		// $this->options['edit'] = array('yw_fl_id'=>array('type'=>'hidden', 'defval'=>YW_FL_XD), 'hb_id'=>array('editable'=>false), 'zzvw_dingdan', 
			// 'note', 'jbr_id', 'happen_date');
			
		// $this->options['linkTables'] = array(
			// 'one2m'=>array(
				// 'zzvw_dingdan'=>array('table'=>'zzvw_dingdan', 'self_link_field'=>'yw_id')
				// )
		// );
		$this->options['parent'] = array('table'=>'zzvw_gys', 'field'=>'hb_id');
	}
	
	protected function getDetailListColumns(){
		$ret = array(
			'zzvw_dingdan'=>array('label'=>g_str('order').g_str('detail_list'), 'editable'=>true, 'legend'=>'',
				'editrules'=>array('required'=>true),
				'formatter'=>'multi_row_edit', 
				'formatoptions'=>array(
					'subformat'=>'temp', 
					'temp'=>"%(defect_id)s的%(wz_id)s %(amount)s%(unit_name)s, 单价%(price)s 元, 已完成%(completed_amount)s%(unit_name)s, 状态: %(dingdan_status_id)s")
				),
		);
		return $ret;
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
