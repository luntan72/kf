<?php
require_once('table_desc.php');

class workflow_prj_type_chip extends table_desc{
    public function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'prj_id',
			'chip_id'
		);
		$this->options['edit'] = array('chip_id');
    } 
}
