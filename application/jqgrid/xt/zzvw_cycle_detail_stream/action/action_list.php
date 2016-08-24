<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_list.php');

class xt_zzvw_cycle_detail_stream_action_list extends xt_zzvw_cycle_detail_action_list{

	protected function handlePost(){
		$this->prepareParams();
        $ret = array();
        $rownum = $this->params['limit']['rows'];
		if ($rownum == 0)
			$rownum = 'ALL';
        $cookie = array('type'=>'rowNum', 'name'=>$this->db_name.'_'.$this->table_name, 'content'=>json_encode(array('rowNum'=>$rownum)));
        $this->saveCookie($cookie);
		$sqls = $this->table_desc->calcSqlComponents($this->params, true);
		$mainFields = $sqls['main']['fields']; //can change table in table_desc
		$limitedSql = $sqls['limit'];	
		unset($sqls['limit']);
		if($this->params['result_type_id'] == '-1')
			$this->params['result_type_id'] = RESULT_TYPE_BLANK;
		if(empty($this->params['result_type_id']) || $this->params['result_type_id'] == RESULT_TYPE_BLANK){
			$sqls['main']['fields'] = "`{$this->table_desc->get('table')}`.`id`"; //can change table in table_desc
			$sql = $this->table_desc->getSql($sqls);	
		}
		else {
			$table = $this->table_desc->get('table');
			$sqls['main']['fields'] = $table.".codec_stream_id, ".$table.".test_env_id, ".$table.".prj_id, ".$table.".result_type_id, COUNT(*) AS cases";
		}
		$sql = $this->table_desc->getSql($sqls);
// print_r($sql."\n");
		$res = $this->tool->query($sql);
		$ret['records'] = $res->rowCount();
		$res->closeCursor();	
        $ret['page'] = $this->params['page'];
        if ($this->params['limit']['rows'] > 0)
            $ret['pages'] = ceil($ret['records'] / $this->params['limit']['rows']);
		else
			$ret['pages'] = 1;
		
		$sqls['main']['fields'] = $mainFields;
		$sqls['limit'] = $limitedSql;
		$sql = $this->table_desc->getSql($sqls);
		$res = $this->tool->query($sql);
        $rows = array();
		$sqlKeys = $this->tool->getSqlKeys();
        while($row = $res->fetch()){
            $row = $this->table_desc->getMoreInfoForRow($row);
			if (!empty($sqlKeys))
				$row = $this->hilightKeys($row, $sqlKeys);
			$rows[] = $row;
        }
		$res->closeCursor();
		
        $ret['rows'] = $rows;
        $ret['sql'] = $sql;
		$ret['sqls'] = json_encode($sqls);
		$ret['keys'] = $sqlKeys;
		$ret['additional'] = $this->table_desc->getStatistics(True);
		return $ret;
	}	
	
	protected function prepareParams(){
		parent::prepareParams();
		$table = $this->table_desc->get('table');
		$this->params['group'] = "{$table}.cycle_id, {$table}.codec_stream_id, {$table}.test_env_id, {$table}.prj_id";
	}
	
	protected function filterParams(){
		$params = parent::filterParams();
//print_r($params['searchConditions']);		
		foreach($params['searchConditions'] as &$v){
			switch($v['field']){
				case 'd_code,zzvw_cycle_detail.summary':
					$v['field'] = 'name,zzvw_cycle_detail_stream.d_code';
					$v['op'] = 'like';
					break;
			}
// print_r($v);
		}
		return $params;
	}
}
?>