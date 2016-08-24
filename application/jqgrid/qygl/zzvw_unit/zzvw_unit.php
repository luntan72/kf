<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');

class qygl_zzvw_unit extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'unit';
// print_r($params);		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'unit_fl_id'=>array('label'=>'分类'),
            'name'=>array('label'=>'名称'),
			'fen_zi'=>array('label'=>'分子'),
			'fen_mu'=>array('label'=>'分母'),
			'standard_unit_id'=>array('label'=>'标准单位', 'data_source_table'=>'zzvw_unit', 'editable'=>false)
        );
		$this->options['edit'] = array('unit_fl_id'=>array('editable'=>false), 'name', 'fen_zi', 'fen_mu', 'standard_unit_id');
		$this->options['add'] = array('unit_fl_id', 'name', 'fen_zi', 'fen_mu', 'standard_unit_id');
	}
	
}
