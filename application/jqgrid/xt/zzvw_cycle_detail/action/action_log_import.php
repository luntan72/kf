<?php

require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_log_import extends action_jqgrid{

	public function handlePost(){	
		$sql = "select cycle_id, logs from cycle_detail where id = {$this->params['id']}";
		$res = $this->tool->query($sql);
		$data = $res->fetch();
		if($_FILES['logfile']["name"]){
			$file_name = $this->tool->uniformFileName($_FILES['logfile']["name"]);
			$fileName = LOG_ROOT."/".$data['cycle_id']."/".$this->params['id']."/".$file_name;				
			$fileName = $this->tool->uniformFileName($fileName);
			$path_parts = pathinfo($fileName);
			$this->tool->createDirectory($path_parts['dirname']);
			move_uploaded_file($_FILES['logfile']["tmp_name"], $fileName);
			if(file_exists($fileName)){	
				$key = $this->tool->getElementId("log_key", array("server"=>"umbrella"));
				$fileList = json_decode($data['logs'], true);
				$tmp = $fileList;
				if(!empty($fileList)&& isset($fileList[$key]) && !in_array($file_name, $fileList[$key]))
					$fileList[$key][] = $file_name;	
				else if(empty($fileList) || !isset($fileList[$key]))
					$fileList[$key] = array($file_name);
				$this->tool->update("cycle_detail", array("logs"=>json_encode($fileList)), "id=".$this->params['id']);
				print_r("log uplod successfully!!!");
				print_r("<input id='logfile_path' type='hidden' name='logfile_path' value='".$fileName."'>");
				if (PHP_OS == 'Linux'){
					$cmd = 'chmod -R a+r '.$fileName;
					$line = exec($cmd, $output, $retVal);	
					if ($retVal){ // failed
						print_r("chmod retVal = $retVal");
						return;
					}
				}
			}	
		}
		
	}
}
?>