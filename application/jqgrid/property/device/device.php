<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金管理

class property_device extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'device';
		$this->options['list'] = array(
			'id',
			'device_type_id',
			'fix_code',
			'name',
			'device_property'=>array('editable'=>true, 'formatter'=>'multi_row_edit', 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'%(property_id)s:%(content)s')),
			'manager_id',
			'owner_id',
			'stock_date',
			'expire_date',
			'isactive',
			'*'=>array('hidden'=>true),
		);
		$this->options['linkTables'] = array(
			'one2m'=>array(
				array('table'=>'device_property'),
			),
		);
	}

	protected function _setSubGrid(){
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'device_id', 'db'=>'property', 'table'=>'device_trace');
	}
	
}
