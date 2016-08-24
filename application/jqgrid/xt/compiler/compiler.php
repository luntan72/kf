<?php

require_once('table_desc.php');

class xt_compiler extends table_desc{
    protected function init($params){
		parent::init($params);
		if(!empty($this->params['container']) && $this->params['container'] == 'select_cart'){
			$this->options['list'] = array(
				'id', 
				'name'=>array('label'=>'Compiler Name'),
				'isactive'=>array('defval'=>ISACTIVE_ACTIVE, 'hidden'=>true)
			);
		}
		else{
			$this->options['list'] = array(
				'id', 
				'name'=>array('editable'=>true, 'unique'=>true, 'editrules'=>array('required'=>true)),
				'os_ids'=>array('editable'=>true),
				//'os_id'=>array('editable'=>true, 'exculed'=>true, 'hidden'=>true, 'hidedlg'=>true),
				'isactive'
			);
		}
		$this->options['gridOptions']['label'] = 'Compiler';
		$this->options['linkTables'] = array('m2m'=>array('os'));
    } 

	public function accessMatrix(){
		// $access_matrix = parent::accessMatrix();
		$access_matrix['all']['all'] = false;
		$access_matrix['admin']['all'] = $access_matrix['assistant_admin']['all'] = true;
		return $access_matrix;
	}
}
