<?php
require_once('importer_base.php');

class xt_zzvw_cycle_importer_update_mqx_log_rc2 extends importer_base{
	protected function _import($fileName){
		return $this->process();
	}

	protected function process(){
print_r("process mqx log rc2");
		$path = LOG_ROOT;
		$dirs = scandir($path);
		$key = $this->tool->getElementId("log_key", array("server"=>"umbrella"));
		foreach($dirs as $dir){
			if("." == $dir || ".." == $dir)
				continue;
			if(preg_match("/^(.*)-Fun-(.*)-(.*)-By_Excel_(.*)$/i", $dir, $matches)){
				$prj_id = $this->tool->getExistedId("prj", array('name'=>$matches[1]), array('name'));
// print_r($prj_id."\n<br />");
				if('error' == $prj_id)
					continue;
				$rel_id = $this->tool->getExistedId("rel", array('name'=>$matches[2]), array('name'));
// print_r($rel_id."\n<br />");
				if('error' == $rel_id)
					continue;
				$res = $this->tool->query("select * from cycle where creater_id = 65 and prj_id = $prj_id and rel_id = $rel_id ");
				while($row = $res->fetch()){
					if(stripos($row['name'], $matches[3]) === false)
						continue;
					$cycle_id = $row['id'];
					$orig_path = LOG_ROOT."/{$dir}";
					$detail_dirs = scandir($orig_path);
					foreach($detail_dirs as $detail_dir){
						if("." == $detail_dir || ".." == $detail_dir)
							continue;
						$detail_path = $orig_path."/".$detail_dir;
						if(preg_match("/^(.*)_(\d*)$/i", $detail_dir, $mtes)){
							$code_res = $this->tool->query("select cycle_detail.* from testcase left join cycle_detail on cycle_detail.testcase_id = testcase.id".
								" where testcase.code = '{$mtes[1]}' and cycle_detail.cycle_id = {$cycle_id}");
							if($info = $code_res->fetch()){
								$log_dirs = scandir($detail_path);
								foreach($log_dirs as $log_dir){
									if("." == $log_dir || ".." == $log_dir)
										continue;
									$logInfo = pathinfo($log_dir);
print_r($log_dir);
print_r("\n<br />");
									if(preg_match("/^(.*)_1$/i", $logInfo['filename'], $mt)){
										if (PHP_OS == 'Linux'){
											$cmd = LOG_ROOT.'/*';//.$log_dir;
											$line = exec($cmd, $output, $retVal);	
											if ($retVal){ // failed
												print_r(">>>>>>>>>>case rm a+r chmod retVal = $retVal"."\n<br />");
												//continue;
											}
											$cmd = 'rm -f '.$detail_path.'/'.$log_dir;

											$line = exec($cmd, $retVal);	

											if ($retVal){ // failed
												print_r(">>>>>>>>>>>rm retVal = $retVal"."\n<br />");
												//continue;
											}
										}
									}
									else{
										// $this->tool->update("cycle_detail", array('logFile'=>$log_dir." ".filesize($detail_path."/".$log_dir)), "id={$info['id']}");
										$logs = json_decode($data["logs"], true);	
										if(!empty($info['logs']) && isset($logs[$key]) && !in_array($log_dir, $logs[$key])){
											$logs[$key][] = $log_dir;
											$update = array('logs'=>json_encode($logs));
										}
										else if(empty($info['logs']) || !isset($logs[$key]))
											$update = array('logs'=>json_encode(array($key=>array($log_dir)));
										if(!empty($update))
											$this->tool->update("cycle_detail", $update, "id={$info['id']}");}	
								}
								
								$new_detail_path = $orig_path."/".$info['id'];
								if (PHP_OS == 'Linux'){
									$cmd = 'mv '.$detail_path.' '.$new_detail_path;
									$line = exec($cmd, $output, $retVal);	
									if ($retVal){ // failed
										print_r(">>>>>>>>>>>>>case folder rename path retVal = $retVal"."\n<br />");
										//continue;
									}
									$cmd = 'chmod -R a+r '.LOG_ROOT.'/*';
									$line = exec($cmd, $output, $retVal);	
									if ($retVal){ // failed
										print_r(".>>>>>>>>>>>>chmod a+r retVal = $retVal"."\n<br />");
										//continue;
									}
								}
							}
						}
						
					}
					
					$new_path = $this->tool->uniformFileName(LOG_ROOT.'/{$cycle_id}");
					if (PHP_OS == 'Linux'){
						$cmd = 'mv '.$orig_path.' '.$new_path;
						$line = exec($cmd, $output, $retVal);	
						if ($retVal){ // failed
							print_r("cycle folder rename  retVal = $retVal"."\n<br />");
							//return;
						}
						$cmd = 'chmod -R a+r '.LOG_ROOT.'/*';
						$line = exec($cmd, $output, $retVal);	
						if ($retVal){ // failed
							print_r("retVal = $retVal"."\n<br />");
							//return;
						}
					}
				}
			}
		}
	}
}

?>
