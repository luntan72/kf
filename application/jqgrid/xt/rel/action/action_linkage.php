<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_linkage.php');

class xt_rel_action_linkage extends action_linkage{
	protected function handlePost(){
        $displayField = $this->table_desc->getDisplayField();
		$sql = "SELECT id, `$displayField` as name FROM `{$this->get('table')}`";
		$sqlFinished = false;
		$where = "1";
		$table = $this->get('table');
		if(!empty($this->params['value'])){
			$where = "{$this->params['field']}=".$this->params['value'];
			if (!empty($this->params['cond']) && $this->params['cond'] == 'REGEXP'){
				$linkTable = $this->table_desc->getLinkTables();
				if(!empty($linkTable)){
					$params = $this->params;
					$params['searchConditions'] = array(array('field'=>$params['field'], 'value'=>$params['value'], 'op'=>'='));
					unset($params['value']);
					unset($params['field']);
					unset($params['cond']);
					$sqls = $this->table_desc->calcSqlComponents($params, false);
					$sqls['main']['fields'] = "`$table`.id, `$table`.`$displayField` as name";
					$sqls['order'] = ' id DESC';
					$sql = $this->table_desc->getSql($sqls);
					$sqlFinished = true;
				}
				else{
					$where = "{$this->params['field']} REGEXP ".$this->db->quote("^{$this->params['value']}$|^{$this->params['value']},|,{$this->params['value']},|,{$this->params['value']}$");
				}
			}
		}
		if(!$sqlFinished)
			$sql .= " WHERE ".$where." ORDER BY id DESC";
// print_r($sql);
		$res = $this->tool->query($sql);
		$rows = $res->fetchAll();
		return json_encode($rows);
	}
	
	
}

?>