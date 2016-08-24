<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//接收采购的货物
class qygl_zzvw_yw_scdj extends qygl_yw{
	protected function init($params){
// print_r($params);
		parent::init($params);
		$this->options['list']['yw_fl_id']['defval'] = YW_FL_SC;
		$this->options['list']['yw_fl_id']['type'] = 'hidden';
		$this->options['list']['hb_id']['label'] = g_str('yg');
		$this->options['list']['hb_id']['data_source_table'] = 'zzvw_yg';
		$this->options['list']['gx_id']['editrules']['required'] = true;
		
		$this->options['caption'] = g_str('scdj');
		$this->options['linkTables'] = array(
			// 'one2one'=>array(
				// array('table'=>'yw_scdj', 'self_link_field'=>'yw_id')
				// ),
			'one2m'=>array(
				'zzvw_pici_scdj'=>array('table'=>'zzvw_pici_scdj',
					'real_table'=>'pici',
					'self_link_field'=>'yw_id',
					// 'link_field'=>'id'
				),
				// 'gx_hjcs'
			)
		);
	}
	
	protected function getDetailListColumns(){
		parent::getDetailListColumns();
		$ret = array(
			'gx_id'=>array('editable'=>true),// 'from'=>'qygl.yw_scdj'),
			'input_pici_id'=>array('data_source_table'=>'zzvw_pici_scdj', 'editable'=>true, 'hidden'=>true),
			'input_pici_amount'=>array('editable'=>true, 'post'=>array('value'=>'?'), 'DATA_TYPE'=>'float', 'hidden'=>true),
			'zzvw_pici_scdj'=>array('label'=>g_str('pici_scdj'), 'formatter'=>'multi_row_edit', 'legend'=>'', 'from'=>'qygl.zzvw_pici_scdj',
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'原有%(defect_id)s的%(wz_id)s %(amount)s, 现余%(remained)s'),
				'editrules'=>array('required'=>true),
			// 'gx_hjcs' => array('editable'=>true, 'from'=>'qygl.gx_hjcs', 'formatter'=>'multi_row_edit','legend'=>'工作环境参数', 
				// 'formatoptions'=>array('subformat'=>'temp', 'temp'=>"%(hjcs_id)s:%(content)s"),
				// 'data_source_db'=>'qygl', 'data_source_table'=>'gx_hjcs')
			)
		);
		return $ret;
	}

	protected function _setSubGrid(){
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'yw_id', 'db'=>'qygl', 'table'=>'zzvw_pici_scdj');
	}
	
    // protected function getButtons(){
        // $buttons = array(
			// 'js'=>array('caption'=>'结束', 'title'=>'结束订单'),
			// 'jh'=>array('caption'=>'重新激活', 'title'=>'重新激活订单')
        // );
        // return array_merge($buttons, parent::getButtons());
    // }
}
