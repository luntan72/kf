<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');

class qygl_wz_cp_zuhe extends table_desc{
	protected function init($params){
		parent::init($params);
// print_r($params);	
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'wz_id'=>array('label'=>'物资', 'formatter'=>'int'),
			// 'input_gx_id'=>array('label'=>'工序', 'data_source_table'=>'gx'),
			'input_wz_id'=>array('label'=>'零件', 'data_source_table'=>'zzvw_wz_cp'),
			'amount'=>array('label'=>'数量'),
        );
	}
}
