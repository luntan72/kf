<?php

require_once('table_desc.php');

class xt_testcase_module extends table_desc{
	protected $testcase_type_ids = '';
	protected $testcase_module_ids = '';
    protected function init($params){
		parent::init($params);
//		$this->options['linktype'] = 'infoLink_ver';
		$this->options['list'] = array('id'=>array('hidden'=>true), 
			'id', 
			'name',
			'description',
			'testcase_type_id',
			'owner_id',
			'creater_id'=>array('hidden'=>true),
			'isactive'=>array('editable'=>false),
			'cases'=>array('excluded'=>true, 'label'=>'Cases', 'search'=>false, 'editable'=>false)
		);
		// $this->options['query'] = array('normal'=>array('key', 'os_id', 'board_type_id', 'chip_id', 'prj_id'=>array('label'=>'Project'), 
			// 'testcase_type_id', 'testcase_category_id', 'testcase_module_id', 'owner_id'), 
			// 'advanced'=>array('testcase_source_id', 'testcase_priority_id', 'auto_level_id', 'edit_status_id', 'last_run', 'active'));
		$this->options['edit'] = array('name', 'description', 'testcase_type_ids', 'owner_id', 'isactive');
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'testcase_module_id', 'db'=>'xt', 'table'=>'testcase_testpoint');
        $this->options['ver'] = '1.0';
		
		$this->options['linkTables'] = array('testcase_type');//=>array('link_table'=>'testcase_module_testcase_type', 'self_link_field'=>'testcase_module_id', 'link_field'=>'testcase_type_id'));
    } 

    // public function getMoreInfoForRow($row){
		// $row = parent::getMoreInfoForRow($row);
		// $res = $this->tool->query("SELECT COUNT(*) as cc FROM testcase WHERE testcase_module_id={$row['id']}");
		// $cc = $res->fetch();
		// $row['cases'] = $cc['cc'];
		
		// // $res = $this->db->query("SELECT GROUP_CONCAT(testcase_type_id) as testcase_type_ids FROM testcase_module_testcase_type WHERE testcase_module_id={$row['id']}");
		// // $type_res = $res->fetch();
		// // $row['testcase_type_ids'] = $type_res['testcase_type_ids'];
		// return $row;
	// }

	// protected function _getLimit($params){
		// $ret = array();
		// //根据用户所在的group来确定testcase_type的可选择范围
		// $res = $this->tool->query("SELECT GROUP_CONCAT(distinct testcase_type_id) as testcase_type_ids FROM group_testcase_type WHERE group_id in ({$this->userInfo->group_ids})");
		// $row = $res->fetch();
		// $this->testcase_type_ids = $row['testcase_type_ids'];
		// $ret['testcase_type_ids'] = array(array('field'=>'testcase_type_id', 'op'=>'in', 'value'=>$row['testcase_type_ids']));
// // print_r($ret);		
		// return $ret;
	// }		
	
	// public function calcSqlComponents($params, $limited = true){
// // print_r($this->testcase_type_ids)	;
		// //需要将testcase_type_id的限制条件强行添加进去
		// $typeExist = false;
		// foreach($params['searchConditions'] as &$item){
			// if(is_array($item)){
				// if($item['field'] == 'testcase_type_ids' || $item['field'] == 'testcase_type_id' ){
					// $typeExist = true;
					// if(strtolower($item['op']) == 'in' || $item['op'] == '='){
						// $values = array();
						// if(is_int($item['value']))
							// $values = array($item['value']);
						// else if(is_string($item['value']))
							// $values = explode(',', $item['value']);
						// else if(is_array($item['value']))
							// $values = $item['value'];
						// $intersect = array_intersect($values, explode(',', $this->testcase_type_ids));
						// if(strtolower($item['op']) == 'in')
							// $item['value'] = $intersect;
						// else
							// $item['value'] = $intersect[0];
					// }	
					// break;
				// }
			// }
		// }
		// if(!$typeExist)
			// $params['searchConditions'][] = array('field'=>'testcase_type_ids', 'op'=>'in', 'value'=>$this->testcase_type_ids);
// // print_r($params);		
		// return parent::calcSqlComponents($params, $limited);
	// }
}
