<?php
require_once('table_desc.php');

class xt_board_type extends table_desc{
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name'=>array('editable'=>true, 'unique'=>true, 'editrules'=>array('required'=>true)),
			'chip_type_id'=>array('editable'=>true),
			'isactive'
		);
		$this->options['gridOptions']['label'] = 'Board Type';
		$this->options['linkTables'] = array('m2m'=>array('chip_type'));
    } 
    public function accessMatrix(){
		// $access_matrix = parent::accessMatrix();
		$access_matrix['all']['all'] = false;
		$access_matrix['admin']['all'] = $access_matrix['assistant_admin']['all'] = true;
		return $access_matrix;
	}
}
?>
