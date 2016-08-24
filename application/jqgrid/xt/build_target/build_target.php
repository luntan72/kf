<?php

require_once('table_desc.php');

class xt_build_target extends table_desc{
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name'=>array('editable'=>true, 'unique'=>true, 'editrules'=>array('required'=>true)),
			'os_id'=>array('editable'=>true),
			//'os_id'=>array('editable'=>true, 'exculed'=>true, 'hidden'=>true, 'hidedlg'=>true),
			'isactive'
		);
		if(!empty($this->params['container']) && $this->params['container'] == 'select_cart'){
			$this->options['list'] = array(
				'id', 
				'name'=>array('label'=>'Build Target Name'),
				'isactive'=>array('defval'=>ISACTIVE_ACTIVE, 'hidden'=>true)
			);
		}
		$this->options['gridOptions']['label'] = 'Build Target';
		$this->options['linkTables'] = array('os');
    } 
}
