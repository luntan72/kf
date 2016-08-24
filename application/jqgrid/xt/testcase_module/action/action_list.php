<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_list.php');
require_once('dbfactory.php');

class xt_testcase_module_action_list extends action_list{
    public function getMoreInfoForRow($row){
		$row = parent::getMoreInfoForRow($row);
		$res = $this->tool->query("SELECT COUNT(*) as cc FROM testcase WHERE testcase_module_id={$row['id']}");
		$cc = $res->fetch();
		$row['cases'] = $cc['cc'];
		
		// $res = $this->db->query("SELECT GROUP_CONCAT(testcase_type_id) as testcase_type_ids FROM testcase_module_testcase_type WHERE testcase_module_id={$row['id']}");
		// $type_res = $res->fetch();
		// $row['testcase_type_ids'] = $type_res['testcase_type_ids'];
		return $row;
	}

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

?>