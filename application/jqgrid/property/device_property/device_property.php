<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金管理

class property_device_property extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->options['displayField'] = 'id';
	}
}
