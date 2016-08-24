<?php
require_once('table_desc.php');

class xt_os extends table_desc{
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name'=>array('editable'=>true, 'unique'=>true, 'editrules'=>array('required'=>true)),
			'testcase_type_id'=>array('editable'=>true),
			'build_target_id'=>array('editable'=>true),
			'compiler_id'=>array('editable'=>true),
			'groups_id'=>array('editable'=>true),
			'isactive'
		);
		if(!empty($this->params['container']) && $this->params['container'] == 'select_cart'){
			unset($this->options['list']);
			$this->options['list'] = array(
				'id', 
				'name'=>array('label'=>'OS Name'),
				'isactive'=>array('defval'=>ISACTIVE_ACTIVE, 'hidden'=>true)
			);
		}
		$this->options['gridOptions']['label'] = 'OS';
		$this->options['linkTables'] = array('m2m'=>array('testcase_type', 'build_target', 'compiler', 'groups'=>array('db'=>'useradmin')));
    } 
}
?>