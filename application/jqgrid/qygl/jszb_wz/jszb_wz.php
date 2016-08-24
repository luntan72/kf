<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');

class qygl_jszb_wz extends table_desc{
	protected function init($params){
		parent::init($params);
// print_r($params);		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'wz_id'=>array('label'=>'物资', 'formatter'=>'int'),
            'jszb_id'=>array('label'=>'技术指标'),
			'min_value'=>array('label'=>'最小值'),
			'max_value'=>array('label'=>'最大值'),
        );
	}
	
}
