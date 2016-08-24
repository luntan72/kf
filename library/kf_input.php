<?php
require_once('kf_cell.php');

class kf_input extends kf_cell{
	protected function init($params, $values){
		parent::init($params, $values);
		$this->is_single_tag = true;
		$this->params['tag'] = 'input';
	}
}
?>