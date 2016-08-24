<?php
require_once('importer_base.php');

class xt_zzvw_cycle_importer_update_detail_log extends importer_base{
	protected function _import($fileName){
		// $this->parse($fileName);
		return $this->process();
	}

	protected function process(){
		$logs = array();
		$wheres = array("logFile != ''", "log_link != ''", "dp_detailid != 0");
		foreach($wheres as $where){
			$sql = 'SELECT id, cycle_id, codec_stream_id, logs, logFile, log_link, dp_detailid, stream_logFile FROM cycle_detail'.
				' WHERE '.$where;
			$res = $this->tool->query($sql);
			while($row = $res->fetch()){
				$log = array();
				if(!empty($row["logFile"])){
					$logfiles = explode(";", $row["logFile"]);
					$log_key = $this->tool->getElementId("log_key", array("server"=>"umbrella"));				
					foreach($logfiles as $fileInfo){
						$values = explode(" ", $fileInfo);
						$log[$log_key][] = trim($values[0]);
					}
				}
				if(!empty($row["log_link"])){
					if(preg_match("/.*href=\"(.*)\" target.*/", $row["log_link"], $matches)){
						$href = $matches[1];
						$log_key = $this->tool->getElementId("log_key", array("server"=>"10.192.225.195", "directory"=>"latestBuild/apollo"));	
						$href = explode("apollo", $href);
						$log[$log_key][] = trim($href[1]);
					}
				}
				if(!empty($row["dp_detailid"])){
					$log_key = $this->tool->getElementId("log_key", array("server"=>"dapeng", "directory"=>"dapeng/showMcuautoRequestDetail"));
					$log[$log_key][] = trim($row["dp_detailid"]);					
				}

		
				if(!empty($row['logs'])){
					$row['logs'] =  json_decode($row['logs'], true);
					if(!empty($log))
						$row['logs'] = $this->tool->array_extends($log, $row['logs']);
					else
						$row['logs'] = $log;
				}
				else{
					if(!empty($log))
						$row['logs'] = $log;
				}
				if(!empty($row['logs'])){
					$row['logs'] = json_encode($row['logs']);
					$this->tool->update("cycle_detail",array("logs"=>$row["logs"]), "id=".$row["id"]);
				}
			}
		}
	}
}

?>
