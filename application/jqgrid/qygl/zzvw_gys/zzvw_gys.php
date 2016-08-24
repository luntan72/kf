<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH.'/jqgrid/qygl/hb/hb.php');
//供应商管理
class qygl_zzvw_gys extends qygl_hb{
	protected function init($params){
		parent::init($params);
// print_r($this->options);		
		$this->options['caption'] = g_str('gys');
		$this->options['list']['hb_fl_id']['defval'] = $this->options['edit']['hb_fl_id']['defval'] = $this->options['add']['hb_fl_id']['defval'] = HB_FL_GYS;
		$this->options['linkTables']['m2m']['wz'] = array('link_table'=>'hb_wz', 'self_link_field'=>'hb_id', 'link_field'=>'wz_id', 'refer_table'=>'wz');
	}
	
	protected function getDetailListColumns(){
		$ret = array(
			'wz_id'=>array('label'=>g_str('provide'), 'editable'=>true, 'data_source_table'=>'wz'),
		);
		return $ret;
	}
	
	protected function _setSubGrid(){
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'zzvw_yw_xd.hb_id', 'db'=>'qygl', 'table'=>'zzvw_yw_xd');
	}
	
	protected function contextMenu(){
		$menu = array(
			'cg'=>'采购',
			'xs'=>'接订单',
			'scdj'=>'生产登记',
		);
		return $menu;
	}
}
