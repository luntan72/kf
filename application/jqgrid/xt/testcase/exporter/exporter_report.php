<?php
require_once('exporter_excel.php');

class xt_testcase_exporter_report extends exporter_excel{
	public function setOptions($jqgrid_action){
		$this->params['sheets'] = array(
			0=>$this->getTestcaseSheet($jqgrid_action)
		);
		if (!empty($this->params['edit_history']))
			$this->params['sheets'][] = $this->getEditHistorySheet($jqgrid_action);
		if (!empty($this->params['test_history']))
			$this->params['sheets'][] = $this->getTestHistorySheet($jqgrid_action);

//		$this->params['sheets'][] = $this->getStatSheet($jqgrid_action);
	}
	
	protected function getTestcaseSheet($jqgrid_action){
		$table_desc = $jqgrid_action->getTable_desc();
		$sheet = array('title'=>'Testcase Information', 'startRow'=>1, 'startCol'=>1);
		$sheet['header']['rows'] = array($this->getTitle($table_desc));
		foreach($sheet['header']['rows'] as $row=>&$rowData){
			foreach($rowData as $col=>&$cell){
				if($cell['name'] == 'id' || $cell['name'] == 'old_id')
					unset($rowData[$col]);
				else
					$cell['hidden'] = false;
			}
		}
		$sheet['data'] = $this->getData('testcase');
//print_r($sheet['header']['rows']);
		return $sheet;
	}
	
	protected function getEditHistorySheet($jqgrid_action){
		$sheet = array('title'=>'Edit History', 'startRow'=>1, 'startCol'=>1);
		$ver = tableDescFactory::get('xt', 'testcase_ver');
		$sheet['header']['rows'] = array($this->getTitle($ver));
		foreach($sheet['header']['rows'] as $row=>&$rowData){
			foreach($rowData as $col=>&$cell){
				if($cell['name'] == 'id' || $cell['name'] == 'old_id')
					unset($rowData[$col]);
				else
					$cell['hidden'] = false;
			}
		}
		$sheet['data'] = $this->getData('testcase_ver');
		return $sheet;
	}
	
	protected function getTestHistorySheet($jqgrid_action){
		$sheet = array('title'=>'Test History', 'startRow'=>1, 'startCol'=>1);
		$cycle_detail = tableDescFactory::get('xt', 'zzvw_cycle_detail_for_report3');
		$sheet['header']['rows'] = array($this->getTitle($cycle_detail));
		$sheet['data'] = $this->getData('zzvw_cycle_detail_for_report3');
		return $sheet;
	}

	protected function getStatSheet($jqgrid_action){
		$sheet = array('title'=>'Test Result Stat', 'startRow'=>1, 'startCol'=>1);
		return $sheet;
	}
	
	protected function getData($table, $searchConditions = array(), $order = array()){
		$table_desc = tableDescFactory::get($this->params['db'], $table);
		$db = dbFactory::get($this->params['db']);
		$group = '';
		switch($table){
			case 'testcase':
				$searchConditions = array(
					array('field'=>'id', 'op'=>'IN', 'value'=>$this->params['id'])
				);
				if (!empty($this->params['prj_ids'])){
					$searchConditions[] = array('field'=>'prj_id', 'op'=>'IN', 'value'=>$this->params['prj_ids']);
				}
				break;
			case 'testcase_ver':
				$searchConditions = array(
					array('field'=>'testcase_id', 'op'=>'IN', 'value'=>$this->params['id'])
				);
				if(empty($this->params['include_editing_versions'])){
					$searchConditions[] = array('field'=>'edit_status_id', 'op'=>'IN', 'value'=>array(EDIT_STATUS_PUBLISHED, EDIT_STATUS_GOLDEN));
				}
				if(!empty($this->params['edit_history'])){
					if (!empty($this->params['edit_from'])){
						$searchConditions[] = array('field'=>'updated', 'op'=>'>=', 'value'=>$this->params['edit_from']);
					}
					if (!empty($this->params['edit_to'])){
						$searchConditions[] = array('field'=>'updated', 'op'=>'<=', 'value'=>$this->params['edit_to']);
					}
				}

				if (!empty($this->params['prj_ids'])){
					$searchConditions[] = array('field'=>'prj_testcase_ver.prj_id', 'op'=>'IN', 'value'=>$this->params['prj_ids']);
					$group = 'prj_testcase_ver.testcase_id, prj_testcase_ver.testcase_ver_id';
				}
				break;
			case 'zzvw_cycle_detail_for_report3':
				$searchConditions = array(
					array('field'=>'testcase_id', 'op'=>'IN', 'value'=>$this->params['id'])
				);
				if(!empty($this->params['test_history'])){
					if (!empty($this->params['test_from'])){
						$searchConditions[] = array('field'=>'finish_time', 'op'=>'>=', 'value'=>$this->params['test_from']);
					}
					if (!empty($this->params['test_to'])){
						$searchConditions[] = array('field'=>'finish_time', 'op'=>'<=', 'value'=>$this->params['test_to']);
					}
				}
				break;
		}
		$sqls = $table_desc->calcSqlComponents(array('db'=>$this->params['db'], 'table'=>$table, 'searchConditions'=>$searchConditions), false);
		if(!empty($group))
			$sqls['group'] = $group;
// print_r($sqls);
		$sql = $table_desc->getSql($sqls, false);
// print_r($sql);
		$res = $db->query($sql);
		$rows = array();
		while($row = $res->fetch()){
			$row = $table_desc->getMoreInfoForRow($row);
			$rows[] = $row; 
		}
		return $rows;
	}
	
	
};

?>
