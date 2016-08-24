<?php
require_once('table_desc.php');

class workflow_prj_type_chip_board extends table_desc{
    public function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'prj_id',
			'chip_id',
			'board_type_id'
		);
		$this->options['edit'] = array('chip_id', 'board_type_id');
    } 
}
