<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//工序管理
class qygl_gx extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'gx_fl_id'=>array('label'=>'工序类型'),
			'name'=>array('label'=>'工序名称'),
			'pre_gx_id'=>array('label'=>'前置工序', 'editable'=>true, 'data_source_table'=>'gx'),
			// 'replaced_wz_id'=>array('label'=>'被置换出的材料', 'data_source_table'=>'zzvw_wz_yl'),
			// 'wz_id'=>array('label'=>'产出品材料', 'data_source_table'=>'zzvw_wz_yl'),
			// 'defect_id'=>array('label'=>'主输入的缺陷'),
			'input_defect_id'=>array('label'=>'主输入可能的缺陷', 'editable'=>'true', 'data_source_table'=>'defect'),
			'has_shell'=>array('label'=>'外壳', 'formatter'=>'select', 'formatoptions'=>array('value'=>array(0=>'', 1=>'有外壳', 2=>'无外壳')),
				'edittype'=>'select', 'editoptions'=>array('value'=>array(0=>'', 1=>'有外壳', 2=>'无外壳')), 'stype'=>'select',
				'editrules'=>array('required'=>true)),
			'need_mj'=>array('label'=>'需要模具', 'formatter'=>'select', 'formatoptions'=>array('value'=>array(0=>'', 1=>'需要', 2=>'不需要')),
				'edittype'=>'select', 'editoptions'=>array('value'=>array(0=>'', 1=>'需要', 2=>'不需要')), 'stype'=>'select',
				'editrules'=>array('required'=>true)),
			'work_type_id'=>array('label'=>'工种', 'editable'=>true),
			'gx_input'=>array('label'=>'输入', 'from'=>'qygl.gx_input', 'formatter'=>'multi_row_edit','legend'=>'输入(不包括主产品)', 
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>"按照%(calc_method_id)s计算需要%(wz_id)s %(amount)s"),
				'data_source_db'=>'qygl', 'data_source_table'=>'gx_input'),
			'gx_output'=>array('label'=>'输出', 'from'=>'qygl.gx_output', 'formatter'=>'multi_row_edit','legend'=>'输出（不包括主产品）', 
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>"按照%(calc_method_id)s计算需要%(wz_id)s %(amount)s"),
				'data_source_db'=>'qygl', 'data_source_table'=>'gx_output'),
			'hjcs_id'=>array('editable'=>true),
			'defect_id'=>array('label'=>'可能的问题', 'editable'=>'true', 'data_source_table'=>'defect'),
			'note'=>array('label'=>'备注')
        );
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'input_defect'=>array('link_table'=>'gx_input_defect', 'self_link_field'=>'gx_id', 'link_field'=>'defect_id', 'refer_table'=>'defect'),
				'defect'=>array('link_table'=>'defect_gx', 'self_link_field'=>'gx_id', 'link_field'=>'defect_id', 'refer_table'=>'defect'),
				'pre_gx'=>array('link_table'=>'gx_pre_gx', 'self_link_field'=>'gx_id', 'link_field'=>'pre_gx_id', 'refer_table'=>'gx'),
				'work_type', 'hjcs'
				),
			'one2m'=>array(
				array('table'=>'gx_input'),
				array('table'=>'gx_output'),
			)
		);
		
		$this->parent_table = 'gx_fl';
		$this->parent_field = 'gx_fl_id';
	}
}
