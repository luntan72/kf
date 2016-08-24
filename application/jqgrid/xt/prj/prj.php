<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_prj/zzvw_prj.php');

class xt_prj extends xt_zzvw_prj{
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name'=>array('label'=>'Prj Name'),
			'os_id',
			// 'chip_type_id',
			'chip_id',
			'board_type_id',
			'description'=>array('hidden'=>true),
			'owner_id',
			'prj_status_id'=>array('defval'=>1, 'editable'=>false, 'hidden'=>true),
			'isactive'=>array('defval'=>ISACTIVE_ACTIVE, 'editable'=>false, 'hidden'=>true),
			'*'=>array('hidden'=>true, 'editable'=>false)
		);
		$this->options['gridOptions']['label'] = 'Project';
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'prj_id', 'db'=>'xt', 'table'=>'zzvw_cycle');
		$this->options['real_table'] = 'prj';
//		$this->options['caption'] = 'Project';
		
		$this->options['edit'] = array('os_id', 'chip_type_id', 'chip_id', 'board_type_id', 'name', 'description', 'owner_id');
    }
	
	public function accessMatrix(){
		// $access_matrix = parent::accessMatrix();
		$access_matrix['all']['all'] = false;
		$access_matrix['admin']['all'] = $access_matrix['assistant_admin']['all'] = true;
		return $access_matrix;
	}
}
