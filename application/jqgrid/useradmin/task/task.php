<?php
require_once('table_desc.php');

class useradmin_task extends table_desc{
    public function init($params){
		parent::init($params);
		
		$this->options['list'] = array(
            'id',
			'task_type_id'=>array('editable'=>false),
			'description'=>array('editable'=>false),
			'task_priority_id'=>array('editable'=>false),
			'deadline'=>array('editable'=>false),
			'progress'=>array('editable'=>false),
			'controller_id'=>array('editable'=>false),
			'task_result_id',
			'comment',
        );
    } 
}

