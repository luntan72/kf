<?php

require_once('table_desc.php');

class xt_testcase_testpoint extends table_desc{
	protected $testcase_module_ids = '';
	protected $testcase_type_ids = '';
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name',
			'testcase_module_id'=>array('label'=>'Testcase Module'),
			'description',
			'isactive'=>array('editable'=>false),
			'cases'=>array('editable'=>false, 'search'=>false),
		);
		$this->options['edit'] = array('name', 'testcase_module_id', 'description');
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'testcase_testpoint_id', 'db'=>'xt', 'table'=>'testcase');
		$this->parent_table = 'testcase_module';
		$this->parent_field = 'testcase_module_id';
    } 
	
    public function getMoreInfoForRow($row){
		$res = $this->tool->query("SELECT COUNT(*) as cc FROM testcase WHERE testcase_testpoint_id={$row['id']}");
		$cc = $res->fetch();
		$row['cases'] = $cc['cc'];
		return $row;
	}
	
	protected function handleFillOptionCondition(){
		//�����û����ڵ�group��ȷ��testcase_type�Ŀ�ѡ��Χ
		$res = $this->tool->query("SELECT GROUP_CONCAT(distinct testcase_type_id) as testcase_type_ids FROM group_testcase_type WHERE group_id in ({$this->userInfo->group_ids})");
		$row = $res->fetch();
		$this->testcase_type_ids = $row['testcase_type_ids'];
		$this->fillOptionConditions['testcase_type_ids'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$row['testcase_type_ids']));
		//����testcase_type��ȷ��testcase_module�Ŀ�ѡ��Χ
		$testcase_module_ids = array();
		$res = $this->tool->query("SELECT testcase_module_id from testcase_module_testcase_type WHERE testcase_type_id in ({$this->testcase_type_ids})");
		while($row = $res->fetch())
			$testcase_module_ids[] = $row['testcase_module_id'];
		$testcase_module_ids = array_unique($testcase_module_ids);
		$this->testcase_module_ids = implode(',', $testcase_module_ids);
		$this->fillOptionConditions['testcase_module_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$testcase_module_ids));
	}		
	
	public function calcSqlComponents($params, $limited = true){
		//��Ҫ��testcase_type_id����������ǿ����ӽ�ȥ
		$exist = false;
		foreach($params['searchConditions'] as &$item){
			if(is_array($item)){
				if($item['field'] == 'testcase_module_id'){
					$exist = true;
					if(strtolower($item['op']) == 'in' || $item['op'] == '='){
						$values = array();
						if(is_int($item['value']))
							$values = array($item['value']);
						else if(is_string($item['value']))
							$values = explode(',', $item['value']);
						else if(is_array($item['value']))
							$values = $item['value'];
						$intersect = array_intersect($values, explode(',', $this->testcase_module_ids));
						if(strtolower($item['op']) == 'in')
							$item['value'] = $intersect;
						else
							$item['value'] = $intersect[0];
					}	
					break;
				}
			}
		}
		if(!$exist)
			$params['searchConditions'][] = array('field'=>'testcase_module_id', 'op'=>'in', 'value'=>$this->testcase_module_ids);
// print_r($params);		
		return parent::calcSqlComponents($params, $limited);
	}
}
