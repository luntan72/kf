<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH.'/jqgrid/qygl/hb/hb.php');
//客户管理
class qygl_zzvw_kh extends qygl_hb{
	protected function init($params){
		parent::init($params);
		$this->options['caption'] = g_str('kh');
		$this->options['list']['hb_fl_id']['defval'] = $this->options['edit']['hb_fl_id']['defval'] = $this->options['add']['hb_fl_id']['defval'] = HB_FL_KH;
		$this->options['linkTables']['m2m']['wz'] = array('link_table'=>'hb_wz', 'self_link_field'=>'hb_id', 'link_field'=>'wz_id', 'refer_table'=>'wz');
	}
	
	protected function getDetailListColumns(){
		$ret = array(
			'wz_id'=>array('label'=>g_str('demand'), 'editable'=>true, 'data_source_table'=>'zzvw_wz_zccp'),
			'history_dingdan'=>array('search'=>false), //历来订单总量
			'current_dingdan'=>array('search'=>false) //当前订单总量
		);
		return $ret;
	}
	
	protected function _setSubGrid(){
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'hb_id', 'db'=>'qygl', 'table'=>'zzvw_yw_jd');
	}
	
	protected function contextMenu(){
		$menu = array(
			'cg'=>'采购',
			'xs'=>'接订单',
			'scdj'=>'生产登记',
		);
		return $menu;
	}
	
    protected function getButtonForList(){
        $buttons = array(
			'ask2review'=>array('caption'=>'申请审核'),
			'sign'=>array('caption'=>'正式签署'),
			'change'=>array('caption'=>'统一调整'), //统一调整工种、职位、基本工资、提成比例等
            // 'scdj'=>array('caption'=>'生产登记'),
			'jsgz'=>array('caption'=>'生成工资单'),
            // 'gz'=>array('caption'=>'发工资'),
        );
        return array_merge($buttons, parent::getButtons());
    }
}
