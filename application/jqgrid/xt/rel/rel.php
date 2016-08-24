<?php

require_once('table_desc.php');

class xt_rel extends table_desc{
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name',
			'os_id'=>array('hidden'=>true, 'label'=>'OS', 'editable'=>true),
			'rel_category_id'=>array('label'=>'Category'),
			'*'=>array('hidden'=>true, 'editable'=>false)
		);
		$this->options['gridOptions']['label'] = 'Release';
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'rel_id', 'db'=>'xt', 'table'=>'zzvw_cycle');
		$this->options['real_table'] = 'rel';
		
		$this->options['linkTables'] = array('m2m'=>array('os'));
		
		$this->options['edit'] = array('os_id', 'chip_type_id', 'chip_id', 'board_type_id', 'name', 'rel_category_id', 'description', 'owner_id');
    } 
	
	public function accessMatrix(){
		// $access_matrix = array('tester'=>array('all'=>false));
		// $access_matrix = parent::accessMatrix();
		$access_matrix['all']['all'] = false;
		$access_matrix['admin']['all'] = $access_matrix['assistant_admin']['all'] = true;
		return $access_matrix;
	}
}
