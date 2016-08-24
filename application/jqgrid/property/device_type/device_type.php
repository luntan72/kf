<?php
require_once('table_desc.php');
//资金管理

class property_device_type extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'device_type';
		$this->options['list'] = array(
			'id',
			'name',
			'property_id'=>array('editable'=>true, 'data_source_db'=>'property', 'data_source_table'=>'property'),
			'note',
		);
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'property'=>array('link_table'=>'device_type_property'),
			),
		);
	}
}
