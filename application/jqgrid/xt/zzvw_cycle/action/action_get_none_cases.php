<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_get_none_cases extends action_jqgrid{
	public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$fileName = REPORT_ROOT."/download/dapeng/no_cases_".$params['id'].".yml";
// print_r($fileName);
		if(file_exists($fileName))
			return $fileName;
	}
}
?>