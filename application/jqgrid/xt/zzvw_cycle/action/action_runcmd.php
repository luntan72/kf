<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/action/action_run.php');
class xt_zzvw_cycle_action_runcmd extends xt_zzvw_cycle_action_run{
	protected function handlePost(){
		$this->params['token_ids'] = $this->getTokenId($this->params['token']);
// print_r($this->params);		
// return 1;
		return parent::handlePost();
	}
	
	protected function getTokenId($token){
		$ret = 0;
		$res = $this->tool->query("SELECT * FROM token WHERE name=:name", array('name'=>$token));
		if($row = $res->fetch())
			$ret = $row['id'];
		return $ret;
	}
}

?>