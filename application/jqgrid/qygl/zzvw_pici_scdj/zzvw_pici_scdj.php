<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//生产登记批次管理
class qygl_zzvw_pici_scdj extends table_desc{
	protected function init($params = array()){
		parent::init($params);
		$this->params['real_table'] = 'pici';
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'yw_id'=>array('hidden'=>false),
            'name'=>array('label'=>g_str('summary'), 'editrules'=>array('required'=>true)),
			'hb_id'=>array('label'=>g_str('yg'), 'editable'=>true, 'editrules'=>array('required'=>true), 'data_source_table'=>'zzvw_yg'),
			'pre_gx_id'=>array('label'=>g_str('from_gx'), 'DATA_TYPE'=>'int', 'editable'=>true, 'data_source_table'=>'gx'),
			// 'input_pici_id'=>array('label'=>'分解的产品', 'data_source_table'=>'zzvw_pici_scdj', 'editable'=>true),
			'wz_id'=>array('label'=>g_str('product'), 'data_source_table'=>'zzvw_wz_cp'),
			'defect_id'=>array(),
			'price'=>array('post'=>'元'),
			'amount'=>array('post'=>array('value'=>'?'), 'min'=>1),
			'ck_weizhi_id'=>array(),
			'remained'=>array(),
			'happen_date'=>array('label'=>g_str('product_date'), 'hidden'=>true),
			'created'=>array('editable'=>false),
        );
		
		$this->options['edit'] = array(
			'pre_gx_id', 
			// 'input_pici_id', 
			'wz_id', 'defect_id', 'price', 'amount', 'ck_weizhi_id'
		);
		// $this->options['displayField'] = 'id';
	}
	
	// protected function getButtons(){
        // $buttons = parent::getButtons();
		// unset($buttons['add']);
		// return $buttons;
	// }
}
