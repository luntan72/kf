<?php
require_once(APPLICATION_PATH.'/../library/cell/text.php');

class kf_date extends kf_text{
	protected function init($params, $values){
		parent::init($params, $values);
		$this->params['date'] = 'date';
	}
}
?>