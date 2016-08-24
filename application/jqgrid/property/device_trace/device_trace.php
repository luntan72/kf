<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金管理

class property_device_trace extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'device_trace';
		$this->options['list'] = array(
			'id',
			'device_id',
			'borrower_id'=>array('data_source_db'=>'useradmin', 'data_source_table'=>'users'),
			'borrow_date'
		);
		$this->options['parent'] = array('table'=>'device', 'field'=>'device_id');
	}

}
