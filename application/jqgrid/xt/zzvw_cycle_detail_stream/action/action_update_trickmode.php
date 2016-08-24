<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail_stream/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_stream_action_process_trickmode extends xt_zzvw_cycle_detail_stream_action_jqgrid{

   public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		//$params['id'] = json_decode($params['id']);//检查$params是否非空
		$params['actions'] = json_decode($params['actions']);
		$actions = implode(",", $params['actions']);
		$params['c_f'] = json_decode($params['c_f']);
		$stream = array();
		$id = array();
		$ret = 0;
		foreach($params['id'] as $k=>$v){
			if(!empty($v)){
				if($params['c_f'][$k]==1){
					//是虚行，找到codec_stream_id， 找到对应id
					$res0 = $this->tool->query("SELECT codec_stream_id, test_env_id, prj_id, compiler_id, build_target_id FROM cycle_detail WHERE id=".$v);
					$info = $res0->fetch();
					if($params['isDel'] == 1){
						$sql1 = "SELECT id FROM cycle_detail WHERE cycle_id=".$params['parent']." AND test_env_id=".$info['test_env_id'].
							" AND codec_stream_id=".$info['codec_stream_id']." AND prj_id in (".$info['prj_id'].")".
							" AND compiler_id=".$info['compiler_id']." AND build_target_id=".$info['build_target_id'].
							" AND testcase_id in ($actions)";	
						$res1 = $this->tool->query($sql1);
						while($data0 = $res1->fetch()){
							$id[] = $data0['id'];
						}	
					}
					else if($params['isDel'] == 0){
						foreach($params['actions'] as $case){
							$res2 = $this->tool->query("SELECT testcase_ver_id FROM prj_testcase_ver 
								LEFT JOIN testcase_ver on testcase_ver.id = prj_testcase_ver.testcase_ver_id
								WHERE prj_testcase_ver.testcase_id=".$case." AND prj_testcase_ver.prj_id=".$info['prj_id'].
								" AND testcase_ver.edit_status_id in (".EDIT_STATUS_PUBLISHED." ,".EDIT_STATUS_GOLDEN.")");
							$ver = $res2->fetch();
							$d_sql3 = "SELECT id, testcase_ver_id FROM cycle_detail".
								" WHERE cycle_id=".$params['parent'].
								" AND testcase_id=".$case.
								" AND codec_stream_id=".$info['codec_stream_id'].
								" AND prj_id=".$info['prj_id'].
								" AND compiler_id=".$info['compiler_id'].
								" AND build_target_id=".$info['build_target_id'];
								" AND test_env_id=".$info['test_env_id'];
							$res3 = $this->tool->query($d_sql3);
							if($d_info = $res3->fetch()){//查看是否已有记录,如果有,更新到最新的ver
								if($d_info['testcase_ver_id'] == $ver['testcase_ver_id'])
									continue;
								$data = array('cycle_id'=>$params['parent'], 'testcase_ver_id'=>$ver['testcase_ver_id']);
								$this->tool->update('cycle_detail', $data, 'id='.$d_info['id']);
							}
							else{
// print_r("No such action:".$case."\n");
								$res4 = $this->tool->query("SELECT testcase_ids FROM codec_stream WHERE id=".$info['codec_stream_id']);
								if($d_info4 = $res4->fetch()){
// print_r($d_info4['testcase_ids']);
									//testcase_ids没有的话就什么都加不上了哦
									if(stripos($d_info4['testcase_ids'], $case) !== false){
										$data = array('cycle_id'=>$params['parent'], 'testcase_ver_id'=>$ver['testcase_ver_id'], 'testcase_id'=>$case,
											'result_type_id'=>RESULT_TYPE_BLANK, 'test_env_id'=>$info['test_env_id'], 'codec_stream_id'=>$info['codec_stream_id'], 'finish_time'=>0,
											'prj_id'=>$info['prj_id'], 'compiler_id'=>$info['compiler_id'], 'build_target_id'=>$info['build_target_id']);
										$this->tool->insert('cycle_detail', $data);
									}
								}
							}

						}
					}
				}
			}
		}
		if($params['isDel'] == 1){
			if(!empty($id)){
				$id = implode(",", $id);
				$this->tool->delete('cycle_detail_step', "cycle_detail_id in (".$id.")");
				$this->tool->delete('cycle_detail', "id in (".$id.") AND cycle_id = {$params['parent']}");
			}
			print_r('success');
		}
	}
	
}

?>