<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_update_codec_gvb.php');

class xt_zzvw_cycle_importer_update_linux_skywalker305 extends xt_zzvw_cycle_importer_update_codec_gvb{
	protected $total = 0;
	
	protected function parse($fileName){
		$str = '';
		$handle = fopen($fileName, 'r');
		if ($handle){
			while(!feof($handle))
			  $data[] = fgets($handle);
			fclose($handle);
		}
		if(!empty($data)){
			$timestamp = date("Y-m-d H:i:s");
			for($row=0; $row<count($data); $row++){
				$row_data = trim($data[$row]);
				if(preg_match("/^(.*?)\s{1,}(.*?)\s{1,}(\d+)\s{1,}(.*)$/i", $row_data , $matches)){//(.*)\s(?=\s)(.*)\s(?=\s)(\d)
					$this->parse_result[$timestamp][$row]['code'] = $matches[1];
					$this->parse_result[$timestamp][$row]['result'] = $matches[2];
				}					
			}
		}
	}
	
	protected function process(){
		if(!empty($this->parse_result)){
			if(!is_array($this->params['prj_ids']))
				$this->params['prj_ids'] = array($this->params['prj_ids']);
			if(!is_array($this->params['compiler_ids']))
				$this->params['compiler_ids'] = array($this->params['compiler_ids']);
			if(!is_array($this->params['build_target_ids']))
				$this->params['build_target_ids'] = array($this->params['build_target_ids']);
			$auto = $update_auto = 0;
			$result_na = $this->tool->getResultId('na');	
			foreach($this->parse_result as $stamp=>$row_data){
// print_r($row_data );
// print_r("\n<BR />");
				if($stamp == 0)
					$stamp = date('Y-m-d H:i:s');
				foreach($row_data as $detail){
					$testcase_id = $this->tool->getExistedId('testcase', array('code'=>trim($detail['code'])), array('code'));
					if($testcase_id != 'error'){
						if(strtolower($detail['result']) == 'timeout')
							$detail['result'] = 'Time Out';
						else if(strtolower($detail['result']) != 'pass')
							$detail['result'] = 'NA';
						$result_type_id = $this->tool->getResultId($detail['result']);
						if('error' == $result_type_id || RESULT_TYPE_BLANK == $result_type_id)
							continue;
						$data =  array('result_type_id'=>$result_type_id, 'finish_time'=>$stamp, 'comment'=>'auto test result', 'tester_id'=>$this->params['owner_id']);
						$cond = "cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
								" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = 0".
								" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
								" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
								" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";
						$res = $this->tool->query("select id, result_type_id, comment from cycle_detail where {$cond}");
						if($row = $res->fetch()){
							if($row['result_type_id'] == RESULT_TYPE_BLANK || $row['result_type_id'] == $result_na){
								$this->tool->update('cycle_detail', $data, "id=".$row['id']);
								$this->tool->updatelastresult($row['id']);
								$auto ++;
							}
							else {
								if($row['comment'] == 'auto test result'){
									$update_auto ++;
								}
							}
						}
					}
				}				
			}
			if(!empty($auto))
print_r($auto." cases have updated at first time"."\n<br />");
			if(!empty($update_auto))
print_r($update_auto." cases have been update"."\n<br />" );
			if(empty($auto) && empty($update_auto))
print_r("No update here!"."\n<br />" );		
		}
	}
};

?>
