<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_list.php');

class xt_testcase_action_list extends action_list{
	private $prj_exist = false;
	protected function filterParams(){
		$params = parent::filterParams();
// print_r($params['searchConditions']);		
		foreach($params['searchConditions'] as &$v){
			switch($v['field']){
				case 'key':
					$v['field'] = 'code,summary';
					$v['op'] = 'like';
					break;
				case 'auto_level_ids':
					$v['field'] = 'auto_level_id';
					$v['op'] = '=';
					break;
				case 'prj_ids':
					$v['field'] = 'prj_id';
					$v['op'] = '=';
					break;
				case 'testcase_priority_ids':
					$v['field'] = 'testcase_priority_id';
					$v['op'] = '=';
					break;
				case 'last_run':
					$v['op'] = '<=';
					break;
			}
			
		}
		return $params;
	}

	public function calcSqlComponents($params, $limited = true){
		// $type_exist = $module_exist = $os_exist = $chip_exist = $board_type_exist = $prj_exist = false;
		// foreach($params['searchConditions'] as $item){
			// if(is_array($item)){
				// switch($item['field']){
					// case 'testcase_type_id':
						// $type_exist = true;
						// break;
					// case 'testcase_module_id':
						// $module_exist = true;
						// break;
					// case 'os_id':
						// $os_exist = true;
						// break;
					// case 'chip_id':
						// $chip_exist = true;
						// break;
					// case 'board_type_id':
						// $board_type_exist = true;
						// break;
					// case 'prj_id':
						// $prj_exist = true;
						// break;
				// }
			// }
		// }
		// if(!$type_exist)
			// $params['searchConditions'][] = array('field'=>'testcase_type_id', 'op'=>'in', 'value'=>$this->testcase_type_ids);
		// if(!$module_exist)
			// $params['searchConditions'][] = array('field'=>'testcase_module_id', 'op'=>'in', 'value'=>$this->testcase_module_ids);
		// if(!$os_exist)
			// $params['searchConditions'][] = array('field'=>'os_id', 'op'=>'in', 'value'=>$this->os_ids);
		// if(!$chip_exist)
			// $params['searchConditions'][] = array('field'=>'chip_id', 'op'=>'in', 'value'=>$this->chip_ids);
		// if(!$board_type_exist)
			// $params['searchConditions'][] = array('field'=>'board_type_id', 'op'=>'in', 'value'=>$this->board_type_ids);
		// if(!$prj_exist)
			// $params['searchConditions'][] = array('field'=>'prj_id', 'op'=>'in', 'value'=>$this->prj_ids);
// print_r($params);		
		$components = parent::calcSqlComponents($params, $limited);
		$components['main']['from'] .= " LEFT JOIN testcase_ver testcase_ver on testcase.id=testcase_ver.testcase_id ".
			" LEFT JOIN prj_testcase_ver on testcase_ver.id=prj_testcase_ver.testcase_ver_id";
		$components['main']['fields'] .= ", group_concat(DISTINCT testcase_ver.id) as ver_ids, ".
				" group_concat(DISTINCT prj_testcase_ver.prj_id) as prj_ids, ".
				" GROUP_CONCAT(distinct prj_testcase_ver.testcase_ver_id) as linked_ver_ids";
				// " group_concat(distinct testcase_ver.auto_level_id) as auto_level_ids, ".
				// " group_concat(distinct testcase_ver.testcase_priority_id) as testcase_priority_ids, ".
				// " group_concat(distinct testcase_ver.owner_id) as owner_ids,".
				// " group_concat(distinct command separator '\\n') as command";
		$components['group'] = 'testcase.id';
// print_r($components);		
		return $components;
	}
	
	public function getMoreInfoForRow($row){
// print_r($row);	
		// $res = $this->tool->query("SELECT group_concat(testcase_ver_id) as linked_ver_ids from prj_testcase_ver where testcase_id={$row['id']}");
		// $tmp = $res->fetch();
		// $row['linked_ver_ids'] = $tmp['linked_ver_ids'];
		// $row['ver_ids'] = '';
		if(!empty($row['linked_ver_ids'])){
			$row['ver_ids'] = $row['linked_ver_ids'];
		
			$sql = "SELECT ".
				" group_concat(distinct auto_level_id) as auto_level_ids, ".
				" group_concat(distinct testcase_priority_id) as testcase_priority_ids, ".
				" group_concat(distinct auto_run_minutes) as auto_run_minutes, ".
				" group_concat(distinct manual_run_minutes) as manual_run_minutes, ".
				" group_concat(distinct owner_id) as owner_ids,".
				" group_concat(distinct command separator '\\n') as command".
				" from testcase_ver".
				" WHERE id in ({$row['ver_ids']})";
			$res = $this->db->query($sql);
			$rr = $res->fetch();
			$row = array_merge($row, $rr);
		}
		return $row;
	}
	
	protected function getSpecialFilters(){
		return array('key', 'os_id', 'board_type_id', 'chip_id', 'prj_id', 'edit_status_id', 
			'owner_id', 'testcase_priority_id', 'auto_level_id', 'command', 'key_fields');
	}
/*	
	protected function specialSql($special, &$ret){
		$this->prj_exist = count($special);
		if ($this->prj_exist){
			$ret['group'] = 'testcase.id';
			$prj_id = false;
			$prj_where = '1';
			foreach($special as $c){
				switch($c['field']){
					case 'prj_id':
						if($c['op'] == '='){
							$prj_id = true;
							$ret['where'] .= ' AND '.$this->tool->generateLeafWhere($c);
						}
						break;
					case 'os_id':
					case 'chip_id':
					case 'board_type_id':
						$prj_where .= ' AND '.$this->tool->generateLeafWhere($c);
						break;
					default:
						$c['field'] = 'testcase_ver.'.$c['field'];
						$ret['where'] .= ' AND '.$this->tool->generateLeafWhere($c);
				}
			}
			if (!$prj_id && $prj_where != '1'){
				$res = $this->tool->query("SELECT GROUP_CONCAT(id) as ids FROM prj WHERE $prj_where AND id IN ($this->prj_ids)");
				$row = $res->fetch();
				if (empty($row)){
					$ret['where'] .= ' AND 0';
				}
				else{
					$ret['where'] .= ' AND '.$this->tool->generateLeafWhere(array('field'=>'prj_id', 'op'=>'IN', 'value'=>$row['ids']));
				}
			}
		}
	}
*/	
	protected function specialSql($special, &$ret){
// print_r($special);
		$prj_id = false;
		$prj_filter = array();
		$prj_where = '1';
		$prj_ids = array();
		$prj_empty = false;
		foreach($special as $i=>$c){
			if(is_int($i))
				$c = array('field'=>$c);
			if(empty($c['value']))
				continue;
			if($c['field'] == "key"){
				$key_value = $c['value'];
			}
			switch($c['field']){
				case 'prj_id':
					$prj_filter = $c;
					break;
				case 'os_id':
				case 'chip_id':
				case 'board_type_id':
					$prj_where .= ' AND '.$this->tool->generateLeafWhere($c);
					break;
				default:
					if($c['field'] == 'key_fields'){
						if(empty($key_value))
							break;
						// print_r($key_value);
						$i = 0;
						$cnt = count($c['value']);
						foreach($c['value'] as $key_field){
							$i++;
							if($key_field == "code" | $key_field == "summary")
								$c['field'] = 'testcase.'.$key_field;
							else
								$c['field'] = 'testcase_ver.'.$key_field;
							$c['value'] = $key_value;
							$c['op'] = 'like';
							if ($i == 1 && $cnt == 1){
								$ret['where'] .= ' AND ('.$this->tool->generateLeafWhere($c).")";
							}
							else if ($i == 1){
								$ret['where'] .= ' AND ('.$this->tool->generateLeafWhere($c);
							}
							else {
								if ($i == $cnt)
									$ret['where'] .= ' OR '.$this->tool->generateLeafWhere($c).")";
								else
									$ret['where'] .= ' OR '.$this->tool->generateLeafWhere($c);
							}
						}
					}
					else{
						if($c['field'] == 'key')
							break;
						$c['field'] = 'testcase_ver.'.$c['field'];
						$ret['where'] .= ' AND '.$this->tool->generateLeafWhere($c);
					}
			}
		}
		if($prj_where != '1'){
// print_r("SELECT GROUP_CONCAT(distinct id) as ids FROM prj WHERE $prj_where AND id IN ($this->prj_ids)");		
			$this->tool->setDb('xt');
			$res = $this->tool->query("SELECT id FROM prj WHERE $prj_where AND id IN ($this->prj_ids)");
			while($row = $res->fetch()){
				$prj_ids[] = $row['id'];
			}
			if(empty($prj_ids))
				$prj_empty = true;
		}
		if($prj_empty){
			$ret['where'] .= ' AND 0';
		}
		elseif(!empty($prj_filter)){
			$v = $prj_filter['value'];
			if(is_string($v))
				$v = explode(',', $v);
			else if (is_int($v))
				$v = array($v);
			if($prj_where != '1'){
				$v = array_intersect($v, $prj_ids);
			}
			if(!empty($v)){
				$ret['where'] .= ' AND '.$this->tool->generateLeafWhere(array('field'=>'prj_id', 'op'=>'IN', 'value'=>$v));
			}
			else
				$ret['where'] .= ' AND 0';
		}
	}
	// End of Calc SQL	
}

?>