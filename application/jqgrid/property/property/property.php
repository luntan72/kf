<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金管理

class property_property extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'property';
		$this->options['list'] = array(
			'id',
			'name',
			'data_type_id',
			'device_type_id'=>array('editable'=>true, 'data_source_db'=>'property', 'data_source_table'=>'device_type'),
			'note',
		);
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'device_type'=>array('link_table'=>'device_type_property'),
			),
		);
	}
}
