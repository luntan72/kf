<?php
require_once('table_desc.php');

class workflow_prj_trace extends table_desc{
    public function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'daily_note_id',
			'prj_id',
			'progress'
		);
		$this->options['edit'] = array('prj_id', 'progress');
    } 
}
