<?php
require_once('exporter_excel.php');

class xt_testcase_module_exporter_excel_testplan extends exporter_excel{

	// protected function init($params = array()){
		// print_r($params);
		// // parent::init($params);
		// // $this->fileName .= '.xlsx';
	// }
	public function setOptions($jqgrid_action){
		// print_r($this->params);
		$titles = array(
			array('index'=>'module', 'width'=>100, 'label'=>'Module', 'cols'=>1)
		);
		$db = dbFactory::get($this->params['db']);
		$res = $db->query("select id, name from prj where id in (".implode(",", $this->params['prj_ids']).")");
		while($row = $res->fetch()){
			$this->params['prjs'][$row['id']] = strtolower($row['name']); 
			$titles[] = array('index'=>strtolower($row['name']), 'width'=>100, 'label'=>$row['name'], 'cols'=>1);
		}
		$titles[] = array('index'=>'owner', 'width'=>100, 'label'=>'Owner', 'cols'=>1);//coreid + nickname
		$data = $this->getData(null);
		$this->params['sheets'][0] = array('title'=>'Test Plan', 'startRow'=>1, 'startCol'=>0, 'header'=>array('rows'=>array($titles)), 'data'=>$data);	
	}
	
	protected function getData($table_desc, $searchConditions = array(), $order = array()){
		$i = 0;
		$data = array();
		$db = dbFactory::get($this->params['db']);
		$useradmin = dbFactory::get("useradmin");
		$ids = implode(',', $this->params['id']);
		$res = $db->query("select id as testcase_module_id, name as module, owner_id from testcase_module where id in (".$ids.")");
		while($row = $res->fetch()){
			$data[$i]['module'] = $row['module'];
			$ns = 0;
			foreach($this->params['prj_ids'] as $prj_id){
				$testplan = "";
				$autoplan = $priorityplan = false;
				$sql = "select count(*) as num, group_concat(distinct ver.auto_level_id) as auto_level_ids,".
					" group_concat(distinct ver.testcase_priority_id) as testcase_priority_ids".
					" from prj_testcase_ver ptv".
					" left join testcase_ver ver on ver.id = ptv.testcase_ver_id".
					" left join testcase on testcase.id = ver.testcase_id".
					" where testcase.testcase_module_id = {$row['testcase_module_id']}".
					" and ptv.prj_id = {$prj_id}".
					" and ver.testcase_priority_id in (1, 2, 3)".
					" and ver.edit_status_id in (1, 2)".
					" and testcase.isactive = 1";
				$result = $db->query($sql);
				if($info = $result->fetch()){
					if($info['num'] == 0){
						$testplan = "NS";
					}
					else{
						$auto_level_ids = explode(",", $info['auto_level_ids']);
						if(1 == count($auto_level_ids) && ('1' == $info['auto_level_ids'] || '4' == $info['auto_level_ids']))
							$autoplan = true;					
						else if(2 == count($auto_level_ids) && in_array('1', $auto_level_ids) && in_array('4', $auto_level_ids))
							$autoplan = true;
						if('1' == $info['testcase_priority_ids'])
							$priorityplan = true;
						
						if($priorityplan && $autoplan)
							$testplan = "BAT + AUTO";
						else if($priorityplan)
							$testplan = "AUTO";
						else if($autoplan)
							$testplan = "BAT";
					}
				}
				else
					$testplan = "NS";
				if('NS' == $testplan)
					$ns ++;
				$data[$i][$this->params['prjs'][$prj_id]] = $testplan;
			}
			$data[$i]['owner'] = "";
			if(!empty($row['owner_id'])){
				$usrRes = $useradmin->query("select username, nickname from users where id = {$row['owner_id']}");
				if($user = $usrRes->fetch()){
					$data[$i]['owner'] = $user['username']."+".$user['nickname'];
				}
			}
			if(count($this->params['prj_ids']) == $ns)
				unset($data[$i]);
			$i ++;
		}
		
		return $data;
	}
};

?>
