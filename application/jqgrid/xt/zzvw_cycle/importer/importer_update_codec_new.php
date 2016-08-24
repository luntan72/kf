<?php
require_once('importer_base.php');

class xt_zzvw_cycle_importer_update_codec_new extends importer_base{
	protected $total = 0;
	
	protected function parse($fileName){
		$str = '';
		$handle = fopen($fileName, 'r');
		if ($handle){
			while(!feof($handle))
			  $data[] = fgets($handle);
			fclose($handle);
		}
		$i = 0;
		if(!empty($data)){
			for($row=0; $row<count($data); $row++){
				$row_data = trim($data[$row]);
				if(preg_match("/^Result:\/mnt\/src(.*)\s+(PASS)$/i", $row_data, $matches)){
					$summary = trim($matches[1]);
					$this->parse_result[$summary] = 'PASS';
				}
			}
		}
	}
	
	protected function process(){
		$update = $total = $update_once = 0;
		if(!is_array($this->params['prj_ids']))
			$this->params['prj_ids'] = array($this->params['prj_ids']);
		if(!is_array($this->params['compiler_ids']))
			$this->params['compiler_ids'] = array($this->params['compiler_ids']);
		if(!is_array($this->params['build_target_ids']))
			$this->params['build_target_ids'] = array($this->params['build_target_ids']);
		if(!empty($this->parse_result)){
			foreach($this->parse_result as $summary=>$result){
				if($result != 'PASS')
					continue;
				$res = $this->tool->query("SELECT id FROM testcase WHERE summary = '{$summary}' LIMIT 1");
				if($row = $res->fetch()){
					$update = array("testcase_id"=>$row['id'], 'result_type_id'=>RESULT_TYPE_PASS, 'finish_time'=>date('Y-m-d H:i:s'), 'tester_id'=>156, 'comment'=>"update by linux codec auto tool");
					$cond = "cycle_id = {$this->params['id']} AND testcase_id = {$row['id']}".
						" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = 0".
						" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
						" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
						" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";
					$d_res = $this->tool->query("SELECT id, result_type_id FROM cycle_detail WHERE {$cond}");
					if($info = $d_res->fetch()){
						if(RESULT_TYPE_BLANK == $info['result_type_id']){
							$update_once ++;
							$this->tool->update("cycle_detail", $update, "id=".$info['id']);
						}
						$total ++;
					}
				}
			}
		}
		// print_r("Process Done!");
		print_r("Total: ".$total."\n");
		print_r("Update: ".$update_once." this time!");
	} 
};

?>
