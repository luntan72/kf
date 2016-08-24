<?php

require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_log_download extends action_jqgrid{

	public function handlePost(){				
		$res = $this->tool->query("select cycle_id from cycle_detail where id = {$this->params['id']}");
		$data = $res->fetch();
		$path = LOG_ROOT."/".$data['cycle_id']."/".$this->params['id'];			
		$path = $this->tool->uniformFileName($path);	
		$fileName = $path."/".$this->params['fileName'];
		$fileName = $this->tool->uniformFileName($fileName);
		if(!file_exists($fileName))
			return;
		else
			return $fileName;
	}
}
?>