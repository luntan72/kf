<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/importer/importer_cycle.php');

class xt_zzvw_cycle_importer_update_codec_cte extends xt_zzvw_cycle_importer_cycle{
	protected $total = 0;
	
	protected function process(){
		if(!empty($this->parse_result)){
			if(!is_array($this->params['prj_ids']))
				$this->params['prj_ids'] = array($this->params['prj_ids']);
			if(!is_array($this->params['compiler_ids']))
				$this->params['compiler_ids'] = array($this->params['compiler_ids']);
			if(!is_array($this->params['build_target_ids']))
				$this->params['build_target_ids'] = array($this->params['build_target_ids']);
			$auto = $update_auto = 0;	
			foreach($this->parse_result as $data){
				foreach($data as $caseInfo){
					if(strtolower($caseInfo['result']) == 'pass' ){
						$testcase_id = $this->tool->getExistedId('testcase', array('code'=>trim($caseInfo['code'])), array('code'));//不更新
						// $res = $this->tool->query("select testcase_ver_id from prj_testcase_ver where prj_id = ".$prj_id." AND testcase_id = ".$testcase_id);
						// if($info = $res->fetch()){
							// $testcase_ver_id = $info['testcase_ver_id']
						// }
						if($testcase_id == 'error')
							continue;
						$result_type_id = $this->tool->getResultId($caseInfo['result']);
						if('error' == $result_type_id || RESULT_TYPE_BLANK == $result_type_id)
							continue;
						$cond = "cycle_id = {$this->params['id']} AND testcase_id = {$testcase_id}".
								" AND test_env_id = {$this->params['test_env_id']} AND codec_stream_id = 0".
								" AND prj_id in (".implode(",", $this->params['prj_ids']).")".
								" AND compiler_id in (".implode(",", $this->params['compiler_ids']).")".
								" AND build_target_id in (".implode(",", $this->params['build_target_ids']).")";
								
						$update = array('result_type_id'=>$result_type_id, 'comment'=>$caseInfo['comment'], 'finish_time'=>date('Y-m-d H:i:s'),
							'tester_id'=>$this->params['owner_id']);
						$res = $this->tool->query("select id, result_type_id, comment from cycle_detail where {$cond}");
						if($row = $res->fetch()){
							if(0 == $row['result_type_id']){
								$this->tool->update('cycle_detail', $update, "id=".$row['id']);
								$this->tool->updatelastresult($row['id']);
								$auto ++;
							}
							else {
									$update_auto ++;
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
