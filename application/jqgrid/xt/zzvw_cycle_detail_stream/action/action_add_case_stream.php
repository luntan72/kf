<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail_stream/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_stream_action_add_case_stream extends xt_zzvw_cycle_detail_stream_action_jqgrid{

	public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		//$params['id'] = json_decode($params['id']);
		$params['testcase_id'] = json_decode($params['testcase_id']);
		if(!is_array($params['prj_ids']))
			$params['prj_ids'] = array($params['prj_ids']);
		if(!is_array($params['testcase_type_ids']))
			$params['testcase_type_ids'] = array($params['testcase_type_ids']);
		
		$res = $this->tool->query("select group_id from cycle where id=".$params['parent']);
		if($row = $res->fetch()){
			$params['group_id'] = $row['group_id'];//default 1
		}
		else{
			return "error";
		}
		
		$cycle = $this->tool->getlinkFieldIds("cycle", $params['parent']);	
		
// print_r($params);	
		foreach($params['id'] as $streamid){
			//static $caseinfo = array();
			$trickmodes = $actions = array();
			$sql = "select stream.testcase_ids as trickmode_ids, type.testcase_ids as testcase_ids".
				" from codec_stream stream left join codec_stream_type type on type.id = stream.codec_stream_type_id".
				" where stream.id=".$streamid;
			$res = $this->tool->query($sql);
			if($info = $res->fetch()){
				if(!empty($info['trickmode_ids'])){
					$actions = explode(',',  $info['trickmode_ids']);
				}
				if(!empty($actions)){//非空，查看case是否在actionlist里
					foreach($actions as $tm){
						if(in_array($tm, $params['testcase_id']))
							$trickmodes[] = $tm;
					}
				}
				// if(GROUP_FAS == $params['group_id']){
					if(empty($trickmodes)){//不在actionlist或者trickmodes原本为空，就查看case是否在caselist里
						if(!empty($info['testcase_ids'])){
							$cases = explode(',', $info['testcase_ids']);
							foreach($cases as $tc){
								if(in_array($tc, $params['testcase_id']))
									$trickmodes[] = $tc;
							}
						}
					}
				// }
				unset($actions);
			}
			if(!empty($trickmodes)){
				$sql = "SELECT ptv.testcase_id, ptv.testcase_ver_id FROM prj_testcase_ver ptv".
					" LEFT JOIN testcase ON testcase.id = ptv.testcase_id".
					" LEFT JOIN testcase_ver ver ON ver.id = ptv.testcase_ver_id";
				$more = " AND ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.", ".EDIT_STATUS_GOLDEN.")".
					" AND testcase.isactive = ".ISACTIVE_ACTIVE." LIMIT 1";
				foreach($params['prj_ids'] as $prj_id){
					foreach($trickmodes as $trickmode){
						foreach($params['testcase_type_ids'] as $testcase_type_id){
							$tmp_sql = $sql." WHERE ptv.testcase_id={$trickmode} AND ptv.prj_id={$prj_id}".
								" AND testcase.testcase_type_id={$testcase_type_id}".$more;
							$res = $this->tool->query($tmp_sql);
							$update = array();
							if($case = $res->fetch()){
								if($case['testcase_ver_id']){
									$new_sql = "SELECT id, testcase_ver_id, result_type_id FROM cycle_detail WHERE cycle_id=".$params['parent'].
										" AND testcase_id = {$case['testcase_id']} AND codec_stream_id = {$streamid}".
										" AND test_env_id = {$params['test_env_id']} AND prj_id = {$prj_id}";
									foreach($cycle['compiler_id'] as $compiler_id){
										foreach($cycle['build_target_id'] as $build_target_id){
											$tmp_new_sql = $new_sql." AND compiler_id={$compiler_id} AND build_target_id={$build_target_id} LIMIT 1";
											$new_res = $this->tool->query($tmp_new_sql);
											if($new_row = $new_res->fetch()){//用if因为compiler_ids和build_target_ids都是单个的，不是多个的
												//其实不需要查testcase _ver_id,因为case是不可用的，就只是作为add stream用的
												if ($new_row['testcase_ver_id'] != $case['testcase_ver_id'])
													$update['testcase_ver_id'] = $case['testcase_ver_id'];
												//如果result_type_id不为0时，如果replaced，则置0，
												if ($new_row['result_type_id'] != RESULT_TYPE_BLANK){
													if ($params['replaced']){//replace所有case的result_type_id为0
														$update['result_type_id'] = RESULT_TYPE_BLANK;
														$update['finish_time'] = 0;
													}
												}
												if(!empty($update))
													$this->tool->update('cycle_detail', $update, "id=".$new_row['id']);
											}
											else{
												$insert = array('cycle_id'=>$params['parent'], 'testcase_ver_id'=>$case['testcase_ver_id'], 'testcase_id'=>$case['testcase_id'], 
													'result_type_id'=>RESULT_TYPE_BLANK, 'test_env_id'=>$params['test_env_id'], 'codec_stream_id'=>$streamid, 'finish_time'=>0,
													'prj_id'=>$prj_id, 'compiler_id'=>$compiler_id, 'build_target_id'=>$build_target_id);
												$this->tool->insert('cycle_detail', $insert);
											}
										}
									}							
								}
							}
						}
					}
					
				}
				unset($trickmodes);
			}
		}
		// $this->processCycle($params['parent']);
print_r("done");
	}
	
	private function processCycle($cycle_id){
		$res = $this->tool->query("select group_concat(distinct detail.prj_id) as prj_ids, group_concat(distinct detail.compiler_id) as compiler_ids, 
			group_concat(distinct detail.build_target_id) build_target_ids, group_concat(distinct tc.testcase_type_id) as testcase_type_ids
			from cycle_detail detail left join testcase tc on tc.id = detail.testcase_id where detail.cycle_id = $cycle_id");
		if($row = $res->fetch()){
			$this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'build_target_ids'=>$row['build_target_ids'],
				'testcase_type_ids'=>$row['testcase_type_ids']), 'id='.$cycle_id
			);
		}
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Codec';
		$view_params['view_file'] = 'newElement.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
		
		$cols = array();
		$sql = "select prj_ids, testcase_type_ids, test_env_id from cycle where id = ".$params['parent'];
		$res = $this->tool->query($sql);
		$info = $res->fetch();
		$testcase_type_ids = explode(",", $info['testcase_type_ids']);
		foreach($testcase_type_ids  as $testcase_type_id){
			if($testcase_type_id== TESTCASE_TYPE_CODEC)//codec
				$testcase_module_id[] = TESTCASE_MODULE_UNIFORMED_CODEC_TRICKMODES;
			else if($testcase_type_id == TESTCASE_TYPE_FAS)//fas
				$testcase_module_id[] = TESTCASE_MODULE_FAS_TRICKMODES;
		}
		$res = $this->tool->query("select id, name from testcase_type where id in (".$info['testcase_type_ids'].")");
		$testcase_type[0] = '';
		while($row = $res->fetch())
			$testcase_type[$row['id']] = $row['name'];
		if(count($testcase_type_id) == 1)
			$cols[] = array('id'=>'testcase_type_ids', 'name'=>'testcase_type_ids', 'label'=>'Testcase Type', 'editable'=>true, 'hidden'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$testcase_type), 'defval'=>$info['testcase_type_ids'], 
				'type'=>'select', 'editrules'=>array('required'=>true));
		else
			$cols[] = array('id'=>'testcase_type_ids', 'name'=>'testcase_type_ids', 'label'=>'Testcase Type', 'editable'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$testcase_type),
				'type'=>'single_multi', 'init_type'=>'cart', 'single_multi'=>array('db'=>$this->get('db'), 'table'=>'testcase_type', 'label'=>'Testcase Type'), 'editrules'=>array('required'=>true));
		$res = $this->tool->query("select id, name from prj where id in (".$info['prj_ids'].")");
		// $prj[0] = '';
		while($row = $res->fetch())
			$prj[$row['id']] = $row['name'];
		// if(!stripos($info['prj_ids'], ","))
			$cols[] = array('id'=>'prj_ids', 'name'=>'prj_ids', 'label'=>'Prj', 'editable'=>true, 'hidden'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$prj), 
				'type'=>'select', 'editrules'=>array('required'=>true));
		// else
			// $cols[] = array('id'=>'prj_ids', 'name'=>'prj_ids', 'label'=>'Prj', 'editable'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$prj), 
				// 'type'=>'single_multi', 'init_type'=>'cart', 'single_multi'=>array('db'=>$this->get('db'), 'table'=>'prj', 'label'=>'Prj'), 'editrules'=>array('required'=>true));
		
		$cart_data = new stdClass;
		$cart_data->filters = '{"groupOp":"AND","rules":[{"field":"testcase_type_id","op":"in","data":'.$info['testcase_type_ids'].'}, 
			{"field":"testcase_module_id","op":"in","data":'.implode(",", $testcase_module_id).'}, {"field":"prj_id","op":"in","data":'.$info['prj_ids'].'}]}';
		$cols[] = array('id'=>'testcase_id', 'name'=>'testcase_id', 'label'=>'Actions', 'editable'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>array()), 'type'=>'cart', 'cart_db'=>'xt', 'cart_table'=>'testcase', 'cart_data'=>json_encode($cart_data), 'editrules'=>array('required'=>true));
		$res = $this->tool->query("SELECT id, name FROM test_env");
		$env = array();
		$env[0] = '';
		while($row = $res->fetch()){
			$env[$row['id']] = $row['name'];
		}
		$cols[] = array('id'=>'test_env_id', 'name'=>'test_env_id', 'label'=>'Test Env', 'editable'=>true, 'DATA_TYPE'=>'text', 'editoptions'=>array('value'=>$env), 'type'=>'select', 'defval'=>$info['test_env_id'], 'editrules'=>array('required'=>true));
		$view_params['cols'] = $cols;
		return $view_params;
	}
	
}

?>