<?php

require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_log_delete extends action_jqgrid{

	public function handlePost(){
		$sql = "select cycle_id, logs from cycle_detail where id = {$this->params['id']}";
		$res = $this->tool->query($sql);
		$data = $res->fetch();
		$file_name = explode(",", $this->params['fileName']);
		
		$path = LOG_ROOT."/".$data['cycle_id']."/".$this->params['id'];
		$path = $this->tool->uniformFileName($path);
		$fileName = $path."/".$file_name[0];
		$fileName = $this->tool->uniformFileName($fileName);;
		if(!file_exists($fileName)){
			return;
		}
			
		if (PHP_OS == 'Linux'){
			$cmd = 'chmod -R a+r '.$fileName;
			$line = exec($cmd, $output, $retVal);	
			if ($retVal){ // failed
				print_r("chmod retVal = $retVal");
				return;
			}
			$cmd = 'rm -f '.$fileName;
			$line = exec($cmd, $output, $retVal);	
			if ($retVal){ // failed
				print_r("rm retVal = $retVal");
				return;
			}
		}
		$key = $this->tool->getElementId("log_key", array("server"=>"umbrella"));
		$fileList = json_decode($data['logs'], true);
		if(!empty($fileList[$key] && in_array($file_name[0], $fileList[$key]))){
			$logFileList = array();
			$fileList[$key] = array_diff($fileList[$key], array($file_name[0]));
			$this->tool->update("cycle_detail", array("logs"=>json_encode($fileList)), "id=".$this->params['id']);
			foreach($fileList as $key=>$fileInfo){
				if(empty($fileInfo))
					continue;
				$res = $this->tool->query("SELECT server, directory, is_url FROM log_key WHERE id={$key} LIMIT 1");
				if($info = $res->fetch()){
					foreach($fileInfo as $filename){
						if(stripos("/", $filename) === 0 || stripos("\\", $filename) === 0)
							$filename = substr($filename, 0, 1);
						switch($info["server"]){
							case "umbrella":
								$logFileList[] = $filename.",".$info['is_url'].",".basename($filename);
								break;
							case "dapeng":
								$logFileList[] = "http://".$info["server"]."/".$info["directory"]."/".$filename."/None/None/None/".",".$info['is_url'].",Dapeng Log";
								break;
							default:
								$logFileList[] = "http://".$info["server"]."/".$info["directory"]."/".$filename.",".$info['is_url'].",".basename($filename);
								break;
						}
					}
				}
				else
					continue;
			}
			return json_encode(array('id'=>$this->params['id'], 'logs'=>implode(";", $logFileList)));
		}
	}
}
?>