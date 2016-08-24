<?php

require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_add_mcu_case extends action_jqgrid{

   public function handlePost(){
		//只添加case，env和codec在case 添加成功之后在detail中添加
		//$params = $this->parseParams();
		$params = $this->params;
		//$params['id'] = json_decode($params['id']);
		$real_table = $this->get('real_table');
		$sql = "SELECT prj_ids, compiler_ids, build_target_ids, test_env_id, testcase_type_ids FROM cycle WHERE id=".$params['parent'];
		$res = $this->tool->query($sql);
		if($info = $res->fetch()){
			$cycle = $info;
		}
		else
			return "error";
		if(stripos($cycle['testcase_type_ids'], ",") !== false)
			$cycle['testcase_type_ids'] = explode(",", $cycle['testcase_type_ids']);
		else
			$cycle['testcase_type_ids'] = array($cycle['testcase_type_ids']);
		$i = 0;
		if(!is_array($params['new_prj_ids']))
			$params['new_prj_ids'] = array($params['new_prj_ids']);
		foreach($params['id'] as $testcase_id){	
			foreach($params['new_prj_ids'] as $prj_id){
				$row = array();
				$sql = "SELECT prj_testcase_ver.prj_id, prj_testcase_ver.testcase_ver_id, prj_testcase_ver.testcase_id FROM prj_testcase_ver 
					left join testcase_ver on testcase_ver.id = prj_testcase_ver.testcase_ver_id
					left join testcase on testcase.id = prj_testcase_ver.testcase_id
					where prj_testcase_ver.prj_id = {$prj_id}".
					" and prj_testcase_ver.testcase_id={$testcase_id}".
					" and testcase_ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.", ".EDIT_STATUS_GOLDEN.")".
					" and testcase.isactive = ".ISACTIVE_ACTIVE;	
				$res = $this->tool->query($sql);
	// print_r($params['id']);
				if($caseInfo = $res->fetch()){
					$row = $caseInfo;
				}
				else{
					$sql0 = "SELECT testcase_ver.id as testcase_ver_id, testcase.id as testcase_id".
						" FROM testcase_ver left join testcase on testcase.id = testcase_ver.testcase_id".
						" where testcase.id={$testcase_id}".
						" and testcase_ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.", ".EDIT_STATUS_GOLDEN.")".
						" and testcase.isactive = ".ISACTIVE_ACTIVE." order by testcase_ver.id desc";	
					$res0 = $this->tool->query($sql0);
					if($caseInfo0 = $res0->fetch()){
						$row = $caseInfo0;
						$row['prj_id'] = $prj_id;
					}
				}
					// $isexist = false;
					//默认case页只有一条case，不计env，因为可以在add env里面添加
				if(!empty($row)){
					foreach($params['compiler_ids'] as $compiler_id){
						if(empty($compiler_id))continue;
						foreach($params['build_target_ids'] as $build_target_id){
							if(empty($build_target_id))continue;
							$detail_sql = "SELECT * FROM cycle_detail WHERE cycle_id = {$params['parent']}".
								" AND testcase_id = {$row['testcase_id']} AND prj_id = {$row['prj_id']}".
								" AND compiler_id = {$compiler_id} AND build_target_id = {$build_target_id}".
								" AND codec_stream_id = 0";//compiler + test_env + build_target
							$detail_res = $this->tool->query($detail_sql);
							if($detail_row = $detail_res->fetch()){
				// print_r($detail_row);
								// $isexist = true;
								//ver不等时，update
								if(empty($detail_row['codec_stream_id'])){
									$datas = array();
									if ($detail_row['testcase_ver_id'] != $row['testcase_ver_id'])
										$datas['testcase_ver_id'] = $row['testcase_ver_id'];
									//如果result_type_id不为0时，如果replaced，则置0，
									if ($detail_row['result_type_id'] != RESULT_TYPE_BLANK){
										if ($params['replaced']){//replace所有case的result_type_id为0
											$datas['result_type_id'] = RESULT_TYPE_BLANK;
											$datas['finish_time'] = 0;
										}
									}
				// print_r($datas);
				// print_r($detail_row);
									if(!empty($datas)){
										$i++;
										$this->tool->update($real_table, $datas, "id=".$detail_row['id']);
									}
								print_r($i);
								}
							}
							else{
								$i++;
								$data = array('cycle_id'=>$params['parent'], 'testcase_ver_id'=>$row['testcase_ver_id'], 'testcase_id'=>$row['testcase_id'], 
									'result_type_id'=>RESULT_TYPE_BLANK, 'codec_stream_id'=>0, 'test_env_id'=>$cycle['test_env_id'], 'compiler_id'=>$compiler_id, 
									'build_target_id'=>$build_target_id, 'prj_id'=>$row['prj_id'], 'finish_time'=>0);
								$this->tool->insert($real_table, $data);
							}
						}
					}
				}
			}
		}
		if(!empty($i))
			return 'sucess';
		// $this->processCycle($params['parent']);
	}
	private function processCycle($cycle_id){
		$res = $this->tool->query("select group_concat(distinct detail.prj_id) as prj_ids, group_concat(distinct detail.compiler_id) as compiler_ids, 
			group_concat(distinct detail.build_target_id) build_target_ids, group_concat(distinct tc.testcase_type_id) as testcase_type_ids
			from cycle_detail detail left join testcase tc on tc.id = detail.testcase_id where detail.cycle_id = $cycle_id");
		if($row = $res->fetch()){
			if(!empty($row['prj_ids'])){
				$this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'build_target_ids'=>$row['build_target_ids'],
					'testcase_type_ids'=>$row['testcase_type_ids']), 'id='.$cycle_id
				);
			}
		}
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Mcu';
		$view_params['view_file'] = 'newElement.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
		
		$cols = array();
		$sql = "select prj_ids, compiler_ids, build_target_ids, test_env_id from cycle where id = ".$params['parent'];
		$res = $this->tool->query($sql);
		$info = $res->fetch();
		$res = $this->tool->query("select id, name from prj where id in (".$info['prj_ids'].")");
		$prj[0] = '';
		while($row = $res->fetch())
			$prj[$row['id']] = $row['name'];
		$cols[] = array('id'=>'new_prj_ids', 'name'=>'new_prj_ids', 'label'=>'Prj', 'editable'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$prj), 
				'type'=>'single_multi', 'init_type'=>'single', 'single_multi'=>array('db'=>$this->get('db'), 'table'=>'prj', 'label'=>'Prj'), 'editrules'=>array('required'=>true));
		$res = $this->tool->query("select id, name from build_target where id in (".$info['build_target_ids'].")");
		while($row = $res->fetch())
			$build_taget[$row['id']] = $row['name'];
		$cols[] = array('id'=>'build_target_ids', 'name'=>'build_target_ids', 'label'=>'Targets', 'editable'=>true, 'hidden'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$build_taget), 
			'type'=>'checkbox', 'editrules'=>array('required'=>true));
		$res = $this->tool->query("select id, name from compiler where id in (".$info['compiler_ids'].")");
		while($row = $res->fetch())
			$compiler[$row['id']] = $row['name'];
		$cols[] = array('id'=>'compiler_ids', 'name'=>'compiler_ids', 'label'=>'IDEs', 'editable'=>true, 'hidden'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$compiler), 
			'type'=>'checkbox', 'editrules'=>array('required'=>true));
		$view_params['cols'] = $cols;
// print_r($cols);
		return $view_params;
	}
}

?>