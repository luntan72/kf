<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
class qygl_gx_wz extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'gx_id'=>array('data_source_table'=>'zzvw_gx_cg_sc'), //生产性工序
			'wz_id'=>array(),
			'min_kc'=>array(),
			'max_kc'=>array(),
			'pd_days'=>array('post'=>'天'),
			'chengpinlv'=>array('post'=>'%'),
			'defect_gx_wz'=>array('label'=>'缺陷及单价等', 'legend'=>'', 
				'data_source_table'=>'defect_gx_wz', 'formatter'=>'multi_row_edit', 'hidden'=>true,
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'缺陷:%(defect_id)s, 单价:%(price)s, 位置:%(ck_weizhi_id)s, 当前库存:%(remained)s')),
        );
		
		$this->options['add'] = array('gx_id', 'min_kc', 'max_kc', 'pd_days', 'chengpinlv', 'defect_gx_wz');
		$this->options['linkTables'] = array(
			'one2m'=>array(
				'defect_gx_wz'=>array('link_table'=>'defect_gx_wz', 'self_link_field'=>'gx_wz_id', 'link_field'=>'defect_id'),
				),
		);
	}
}
