<?php
require_once('action_jqgrid.php');
	
class xt_zzvw_cycle_detail_action_get_linkage extends action_jqgrid{
	protected function handlePost(){
		$params = $this->params;
		$ret = "";
		$func = "get_".$params["dst"];
		if(method_exists($this, $func))
			$ret = $this->$func();
		return $ret;
	}
	
	protected function get_auto_level(){
		$params = $this->params;
		$sql = "SELECT DISTINCT auto_level.id AS id, auto_level.name AS name FROM cycle_detail LEFT JOIN testcase_ver ON cycle_detail.testcase_ver_id=testcase_ver.id LEFT JOIN auto_level ON testcase_ver.auto_level_id=auto_level.id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "cycle_detail.cycle_id=".$params['value'];
		}
		$where .= " AND name is not null";
		$sql .= " WHERE $where ORDER BY name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
	
	protected function get_creater(){
		$params = $this->params;
		$where = "1";
		if($params['value'] && $params['field'] == "prj_id")
			$where .= " AND cycle_prj.prj_id IN (".$params['value'].")";
		if($params['parent']){
			$res = $this->tool->query("select group_id from cycle where id=".$params['parent']);
			if($info = $res->fetch()){
				if(!empty($info['group_id']))
					$where .= " AND cycle.group_id = ".$info['group_id'];
			}
		}
		$res = $this->tool->query("SELECT group_concat(distinct cycle.creater_id) as ids FROM cycle".
			" left join cycle_prj on cycle_prj.cycle_id = cycle.id WHERE ".$where);
		if($info = $res->fetch()){
			$userList = $this->userAdmin->getUserList(array('id'=>$info['ids'], 'blank_item'=>true));
		}
		foreach($userList as $k=>$v){
			$users[] = array('id'=>$k, 'name'=>$v);
		}
		return json_encode($users);
	}
	
	protected function get_creater_cycle(){
		$params = $this->params;
		$where = "1";
		$left_join = "";
		if($params['value'] && $params['field'] == "creater_id")
			$where .= " AND cycle.creater_id IN (".$params['value'].")";
		if(!empty($params['prj_id']))
			$where .= " AND cycle_prj.prj_id IN (".$params['prj_id'].")";
		else{
			if(!empty($params['os_id']))
				$where .= " AND prj.os_id IN (".$params['os_id'].")";
			if(!empty($params['chip_id']))
				$where .= " AND prj.chip_id IN (".$params['chip_id'].")";
			if(!empty($params['board_type_id']))
				$where .= " AND prj.board_type_id IN (".$params['board_type_id'].")";
			$left_join = " LEFT JOIN prj ON prj.id=cycle_prj.prj_id";
		}
		if($params['parent']){
			$res = $this->tool->query("select group_id from cycle where id=".$params['parent']);
			if($info = $res->fetch()){
				if(!empty($info['group_id']))
					$where .= " AND group_id = ".$info['group_id'];
			}
		}

		$res = $this->tool->query("SELECT distinct cycle.id, cycle.name FROM cycle".
			" LEFT JOIN cycle_prj ON cycle_prj.cycle_id = cycle.id". 
			$left_join." WHERE ".$where);
		return json_encode($res->fetchAll());
	}
	
	protected function get_cycle(){
		$params = $this->params;
		$where = "1";
		if($params['value'] && $params['field'] == "prj_id")
			$where .= " AND cycle_prj.prj_id IN (".$params['value'].")";
		if($params['parent']){
			$res = $this->tool->query("select group_id from cycle where id=".$params['parent']);
			if($info = $res->fetch()){
				if(!empty($info['group_id']))
					$where .= " AND cycle.group_id = ".$info['group_id'];
			}
		}
		$res = $this->tool->query("SELECT distinct cycle.id, cycle.name FROM cycle".
			" left join cycle_prj on cycle_prj.cycle_id=cycle.id".
			" WHERE ".$where);
		return json_encode($res->fetchAll());
	}
	
	protected function get_cycle_type(){
		$params = $this->params;
		$where = "1";
		if($params['value'])
			$where .= " AND cycle_detail.prj_id IN (".$params['value'].")";
		if($params['parent']){
			$res = $this->tool->query("select group_id from cycle where id=".$params['parent']);
			if($info = $res->fetch()){
				if(!empty($info['group_id']))
					$where .= " AND cycle.group_id = ".$info['group_id'];
			}
		}
		$sql = "SELECT DISTINCT testcase_type.id as id, testcase_type.name as name FROM cycle".
			" left join cycle_detail on cycle_detail.cycle_id = cycle.id".
			" LEFT JOIN testcase_type on cycle_type_id=testcase_type.id WHERE ".$where;
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
	
	protected function get_module(){
		$params = $this->params;
		$sql = "SELECT DISTINCT testcase_module.id as id, testcase_module.name as name, testcase_type.name as testcase_type FROM cycle_detail".
			" LEFT JOIN testcase ON cycle_detail.testcase_id=testcase.id".
			" LEFT JOIN testcase_module ON testcase.testcase_module_id=testcase_module.id".
			" LEFT JOIN testcase_type on testcase.testcase_type_id = testcase_type.id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "cycle_detail.cycle_id=".$params['value'];
		}
		$where .= " AND testcase_module.name is not null";
		$sql .= " WHERE $where ORDER BY testcase_module.name ASC";
		$res = $this->tool->query($sql);
		$module = array();
		$i = 0;
		while($row = $res->fetch()){
			$module[$i]['id'] = $row['id'];
			$module[$i]['name'] = $row['name'];
			if(strtolower($row['testcase_type']) == 'fas'){
				if(strtolower($row['name']) == 'fas_trickmodes'){
					unset($module[$i]);
				}
			}
			$i++;
		}
		return json_encode($module);
	}
	
	protected function get_testcase_module(){
		$params = $this->params;
		$sql = "SELECT DISTINCT testcase_module.id as id, testcase_module.name as name FROM testcase left join testcase_module on testcase_module.id = testcase.testcase_module_id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "testcase.testcase_type_id =".$params['value'];
		}
		$where .= " AND testcase_module.name is not null";
		$sql .= " WHERE $where ORDER BY name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
		
	protected function get_testcase_priority(){
		$params = $this->params;
		$sql = "SELECT DISTINCT testcase_priority.id as id, testcase_priority.name as name FROM cycle_detail".
			" LEFT JOIN testcase_ver ON cycle_detail.testcase_ver_id=testcase_ver.id".
			" LEFT JOIN testcase_priority ON testcase_ver.testcase_priority_id=testcase_priority.id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "cycle_detail.cycle_id=".$params['value'];
		}
		$where .= " AND testcase_priority.name is not null";
		$sql .= " WHERE $where ORDER BY testcase_priority.name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
	
	protected function get_prj(){
		$params = $this->params;
		$where = "1";
		if($params['parent']){
			$res = $this->tool->query("select group_id from cycle where id=".$params['parent']);
			if($info = $res->fetch()){
				if(!empty($info['group_id']))
					$where .= " AND cycle.group_id = ".$info['group_id'];
			}
		}
// print_r($where);
		$res = $this->tool->query("SELECT distinct prj.id, prj.name FROM cycle".
			" left join cycle_prj on cycle_prj.cycle_id = cycle.id".
			" left join prj on prj.id = cycle_prj.prj_id WHERE ".$where);
		//cycle_type---prj
		return json_encode($res->fetchAll());
	}
}
?>
