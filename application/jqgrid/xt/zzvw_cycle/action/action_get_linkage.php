<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_get_linkage extends action_jqgrid{

	protected function handlePost(){
		$params = $this->params;
		$ret = "";
		$func = "get_".$params["dst"];
		if(method_exists($this, $func))
			$ret = $this->$func();
		return $ret;
	}
	
	private function get_build_target(){
		$params = $this->params;
		$where = "1";
		if(!empty($params['id']) && $params['id'])
			$where = "cycle_id=".$params['id'];
		if($params['value']){
			if('prj_id' == $params['field'])
				$where .= " and cycle_detail.prj_id IN (".$params['value'].")";
			else if('compiler_id' == $params['field']){
				$where .= " and cycle_detail.compiler_id IN (".$params['value'].")";
				if(!empty($params['prj_id']))
					$where .= " and cycle_detail.prj_id IN (".$params['prj_id'].")";
			}
		}
		$res = $this->tool->query("SELECT distinct build_target.id, build_target.name FROM cycle_detail".
			" left join build_target on build_target.id = cycle_detail.build_target_id WHERE ".$where);
		return json_encode($res->fetchAll());
	}
	
	private function get_compiler(){
		$params = $this->params;
		$where = "1";
		if(!empty($params['id']) && $params['id'])
			$where = "cycle_id=".$params['id'];
		if($params['value'] && 'prj_id' == $params['field']){
			if('prj_id' == $params['field'])
				$where .= " and cycle_detail.prj_id IN (".$params['value'].")";
			else if('build_target_id' == $params['field']){
				$where .= " and cycle_detail.build_target_id IN (".$params['value'].")";
				if(!empty($params['prj_id']))
					$where .= " and cycle_detail.prj_id IN (".$params['prj_id'].")";
			}
		}
		$res = $this->tool->query("SELECT distinct compiler.id, compiler.name FROM cycle_detail".
			" left join compiler on compiler.id = cycle_detail.compiler_id WHERE ".$where);
		return json_encode($res->fetchAll());
	}
	
	private function get_cycle(){
		$params = $this->params;
		$where = "1";
		if($params['value'] && 'prj_id' == $params['field'])
			$where = "cycle_detail.prj_id IN (".$params['value'].")";
		$res = $this->tool->query("SELECT distinct cycle.id, cycle.name FROM cycle_detail".
			" left join cycle on cycle.id = cycle_detail.cycle_id WHERE ".$where);
		return json_encode($res->fetchAll());
	}
	
	private function get_testcase_type(){
		$params = $this->params;
		$where = "1";
		if($params['value'] && 'prj_id' == $params['field'])
			$where = "cycle_detail.prj_id IN (".$params['value'].")";
		$res = $this->tool->query("SELECT DISTINCT testcase_type.id as id, testcase_type.name as name FROM cycle".
			" left join cycle_detail on cycle_detail.cycle_id = cycle.id".
			" LEFT JOIN testcase_type on cycle_type_id=testcase_type.id WHERE ".$where);
		return json_encode($res->fetchAll());
	}
	
	private function get_group_rel(){
		$params = $this->params;
		$where = "1";
		if($params['value'] && 'groups_id' == $params['field'])
			$where = "groups_os.groups_id =".$params['value'];
		$sql = "SELECT DISTINCT rel.id, rel.name FROM rel LEFT JOIN os_rel ON os_rel.rel_id = rel.id".
			" LEFT JOIN groups_os ON groups_os.os_id = os_rel.os_id WHERE ".$where." ORDER BY rel.id DESC";
		$res = $this->tool->query($sql);
		$rel = $res->fetchAll();
		return $rel;
	}
	
	private function get_rel(){
		$params = $this->params;
		$where = "1";
		if($params['value'] && 'os_id' == $params['field'])
			$where = "os_id=".$params['value'];
		$sql = "SELECT id, name FROM rel WHERE ".$where;
		$res = $this->tool->query($sql);
		$rel = $res->fetchAll();
		return $rel;
	}
	
	private function get_testers(){
		$params = $this->params;
		$where = "1";
		if($params['value'])
			$where = "id=".$params['value'];
		$sql = "SELECT tester_ids FROM cycle WHERE ".$where;
		$res = $this->tool->query($sql);
		$tester = $res->fetch();
		$tester_ids = explode(",", $tester['tester_ids']);
		foreach($tester_ids as $key=>$val){
			if(empty($val))
				unset($tester_ids[$key]);
		}
		$tester['tester_ids'] = implode(",", $tester_ids);
		$users = $this->userAdmin->getUsers($tester['tester_ids']);
		$testers = array();
		if (!empty($users)){
			$i = 0;
			foreach($users as $user){
				$testers[$i]['id'] = $user['id'];
				$testers[$i++]['name'] = $user['nickname'];
			}
		}
		return $testers;
	}
	
	private function get_assistant_owner(){
		//$params = $this->parseParams();
		$params = $this->params;
		$users = $this->userAdmin->getUsers(implode(",", $params['tester_ids']));
		$testers = array();
		if (!empty($users)){
			$i = 0;
			foreach($users as $user){
				$testers[$i]['id'] = $user['id'];
				$testers[$i++]['name'] = $user['nickname'];
			}
		}
		return json_encode($testers);
	}
}
?>