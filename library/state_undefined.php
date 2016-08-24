<?php
require_once("state.php");
class state_undefined extends state{
	public function handle($line){
		$ret = array();
		$params = $this->context->getParams();
// print_r($params);
		$start_keyword = $params['keyword']['start'];
		if(preg_match($start_keyword, $line, $matches)){
			$ret['start'] = $matches;
			$this->context->shiftToState('start');
		}
		return $ret;
	}
}
?>