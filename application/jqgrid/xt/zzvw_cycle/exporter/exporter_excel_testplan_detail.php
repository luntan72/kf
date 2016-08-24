<?php
require_once('exporter_excel.php');

class xt_zzvw_cycle_exporter_excel_testplan_detail extends exporter_excel{

	// protected function init($params = array()){
		// print_r($params);
		// // parent::init($params);
		// // $this->fileName .= '.xlsx';
	// }
	public function setOptions($jqgrid_action){
		// print_r($this->params);
		$titles = array(
			array('index'=>'id', 'width'=>100, 'label'=>'Id', 'cols'=>1),
			array('index'=>'code', 'width'=>100, 'label'=>'Name', 'cols'=>1),
			array('index'=>'summary', 'width'=>100, 'label'=>'Summary', 'cols'=>1),
			array('index'=>'prj', 'width'=>100, 'label'=>'Project', 'cols'=>1),
			array('index'=>'type', 'width'=>100, 'label'=>'Type', 'cols'=>1),
			array('index'=>'source', 'width'=>100, 'label'=>'Source', 'cols'=>1),
			array('index'=>'category', 'width'=>100, 'label'=>'Category', 'cols'=>1),
			array('index'=>'testpoint', 'width'=>100, 'label'=>'Testpoint', 'cols'=>1),
			array('index'=>'module', 'width'=>100, 'label'=>'Module', 'cols'=>1),
			array('index'=>'auto_level', 'width'=>100, 'label'=>'Auto Level', 'cols'=>1),
			array('index'=>'priority', 'width'=>100, 'label'=>'Priority', 'cols'=>1)
		);
		$db = dbFactory::get($this->params['db']);
		$res = $db->query("select distinct prj.id as prj_id, prj.name as prj from cycle left join prj on prj.id = cycle.prj_ids where cycle.id in (".implode(",", $this->params['id']).")");
		while($row = $res->fetch()){
			$this->params['prjs'][$row['prj_id']] = strtolower($row['prj']); 
			$this->params['prj_ids'][] = $row['prj_id'];
			$titles[] = array('index'=>strtolower($row['prj']), 'width'=>100, 'label'=>$row['prj'], 'cols'=>1);
		}
		$titles[] = array('index'=>'commiter', 'width'=>100, 'label'=>'Commiter', 'cols'=>1);//coreid + nickname
		$data = $this->getData(null);
		$this->params['sheets'][0] = array('title'=>'Testcase', 'startRow'=>1, 'startCol'=>1, 'header'=>array('rows'=>array($titles)), 'data'=>$data);	
	}
	
	protected function getData($table_desc, $searchConditions = array(), $order = array()){
		$i = 0;
		$data = array();
		$db = dbFactory::get($this->params['db']);
		$useradmin = dbFactory::get("useradmin");
		$ids = implode(',', $this->params['id']);
		// $sql = "select group_concat(distinct testcase_id) from cycle_detail where cycle_id in ($ids) order by testcase_id asc";
		$res = $db->query("select distinct testcase_id as testcase_id from cycle_detail where cycle_id in ($ids)");
		while($row = $res->fetch()){
			$sql = "select testcase.id as testcase_id, testcase.code as code, testcase.summary as summary, testcase_type.name as type,".
				" testcase_source.name as source, testcase_category.name as category, testcase_module.name as module,".
				" testcase_testpoint.name as testpoint from testcase".
				" left join testcase_type on testcase_type.id = testcase.testcase_type_id".
				" left join testcase_source on testcase_source.id = testcase.testcase_source_id".
				" left join testcase_category on testcase_category.id = testcase.testcase_category_id".
				" left join testcase_module on testcase_module.id = testcase.testcase_module_id".
				" left join testcase_testpoint on testcase_testpoint.id = testcase.testcase_testpoint_id".
				" where testcase.id={$row['testcase_id']} order by testcase.id asc";
			$result = $db->query($sql);
			
// print_r($this->params['prj_ids']);
			if($info = $result->fetch()){	
				$data[$i] = $info;
				$data[$i]['id'] = $info['testcase_id'];
				unset($data[$i]['testcase_id']);
				$data[$i]['priority'] = "";
				$data[$i]['auto_level'] = "";
				$data[$i]['commiter'] = "";
				foreach($this->params['prj_ids'] as $key=>$prj_id){
					$prj_name = $this->params['prjs'][$prj_id];
					$data[$i][$prj_name] = "";
					$sql0 = "select detail.prj_id, detail.tester_id, testcase_priority.name as priority, auto_level.name as auto_level, result_type.name as result_type".
						" from cycle_detail detail".
						" left join testcase_ver ver on ver.id = detail.testcase_ver_id".
						" left join testcase_priority on testcase_priority.id = ver.testcase_priority_id".
						" left join auto_level on auto_level.id = ver.auto_level_id".
						" left join result_type on result_type.id = detail.result_type_id".
						" where detail.testcase_id = {$info['testcase_id']} and detail.prj_id={$prj_id}".
						" and detail.cycle_id in ($ids) order by detail.finish_time desc";
					$res0 = $db->query($sql0);
					if($detail=$res0->fetch()){
						$prj = $this->params['prjs'][$detail['prj_id']];
						if(stripos($data[$i]['priority'], ",") !== false){
							if($data[$i]['priority'] != $detail['priority'])
								$data[$i]['priority'] = $data[$i]['priority'].", ".$detail['priority'];
						}
						else
							$data[$i]['priority'] = $detail['priority'];
							
						if(stripos($data[$i]['auto_level'], ",") !== false){
							if($data[$i]['auto_level'] != $detail['auto_level'])
								$data[$i]['auto_level'] = $data[$i]['auto_level'].", ".$detail['auto_level'];
						}
						else
							$data[$i]['auto_level'] = $detail['auto_level'];
							
						if(!empty($detail['tester_id'])){
							$usrRes = $useradmin->query("select username, nickname from users where id = {$detail['tester_id']}");
							if($user = $usrRes->fetch()){
								if(stripos($data[$i]['commiter'], ",") !== false){
									if($data[$i]['commiter'] != $user['nickname'])
										$data[$i]['commiter'] = $data[$i]['commiter'].", ".$user['nickname'];
								}
								else
									$data[$i]['commiter'] = $user['nickname'];
							}
						}	
						$data[$i][$prj] = $this->getInfoForCase($detail['result_type']);
					}	
				}
				$i ++;
			}		
		}
		return $data;
	}
	
	protected function getInfoForCase($result){
			return "Y";
	}
};

?>
