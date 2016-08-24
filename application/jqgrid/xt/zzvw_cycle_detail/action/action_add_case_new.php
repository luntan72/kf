<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_action_add_case_new extends xt_zzvw_cycle_detail_action_jqgrid{

   public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$records = $this->caclIDs($params);
		if($records == "error")
			return "error";
		
		// not support adding case from other cycle for mcu
		$sql = "SELECT prj_ids FROM cycle WHERE id=".$params['parent'];
		$res = $this->tool->query($sql);
		if($info = $res->fetch())
			$cycle['prj_id'] = explode(",", $info['prj_ids']);
		else
			return "error";
		
		//process
		$sql = 'SELECT * FROM cycle_detail left join cycle on cycle.id = cycle_detail.cycle_id'.
			' left join testcase_ver on testcase_ver.id=cycle_detail.testcase_ver_id';
		foreach($records as $record){
			$tmp_sql = $sql." WHERE cycle_detail.id={$record} AND cycle_detail.cycle_id={$params['cycle_id']} LIMIT 1";
			$res = $this->tool->query($tmp_sql);
			// find the record
			if($detail = $res->fetch()){			
				// find the newest published or golden case on certain prj
				foreach($cycle['prj_id'] as $prj_id){
					if(!isset($vers[$detail['testcase_id']][$prj_id])){
						$vers_sql = "SELECT prj_testcase_ver.testcase_ver_id FROM prj_testcase_ver left join testcase_ver on testcase_ver.id = prj_testcase_ver.testcase_ver_id".
							" LEFT JOIN testcase on testcase.id = prj_testcase_ver.testcase_id".
							" WHERE prj_testcase_ver.testcase_id=".$detail['testcase_id']." AND prj_testcase_ver.prj_id=".$prj_id.
							" AND testcase_ver.edit_status_id in (".EDIT_STATUS_PUBLISHED." ,".EDIT_STATUS_GOLDEN.")".
							" AND testcase.isactive = 1";
		// print_r($vers_sql."\n");
						$vers_res = $this->tool->query($vers_sql);
						$vers[$detail['testcase_id']][$prj_id] = $vers_res->fetch();
					}
					if ($vers[$detail['testcase_id']][$prj_id]){
						$ver = $vers[$detail['testcase_id']][$prj_id]['testcase_ver_id'];
						$detail_sql = "SELECT * FROM cycle_detail WHERE cycle_id=".$params['parent'].
							" AND testcase_id={$detail['testcase_id']} AND test_env_id={$detail['test_env_id']}".
							" AND prj_id={$prj_id} AND compiler_id={$detail['compiler_id']}".
							" AND codec_stream_id={$detail['codec_stream_id']} AND build_target_id={$detail['build_target_id']}";
						$detail_res = $this->tool->query($detail_sql);
						if($detail_row = $detail_res->fetch()){//testcase + env + codec_stream 唯一确定一条result记录
							$update = array();
							// update to the newest published or golden cases
							if ($detail_row['testcase_ver_id'] != $ver)
								$update['testcase_ver_id'] = $ver;
							// if result is not 0 and replaced is true, then set to 0
							if ($detail_row['result_type_id'] != RESULT_TYPE_BLANK){
								if ($params['replaced']){//replace所有case的result_type_id为0
									$update['result_type_id'] = RESULT_TYPE_BLANK;
									$update['finish_time'] = 0;
								}
							}
							if(!empty($update))
								$this->tool->update('cycle_detail', $update, "id=".$detail_row['id']);
						}
						else {
							$insert = array('cycle_id'=>$params['parent'], 'testcase_ver_id'=>$ver, 'testcase_id'=>$detail['testcase_id'], 
								'result_type_id'=>RESULT_TYPE_BLANK, 'test_env_id'=>$detail['test_env_id'], 'codec_stream_id'=>$detail['codec_stream_id'], 'finish_time'=>0,
								'prj_id'=>$prj_id, 'compiler_id'=>$detail['compiler_id'], 'build_target_id'=>$detail['build_target_id']);
							$this->tool->insert('cycle_detail', $insert);
						}
						unset($ver);
					}
				}
			}	
		}
	}
	
}

?>