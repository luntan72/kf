<?php

require_once('table_desc.php');

class xt_testcase_type extends table_desc{
    protected function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id', 
			'name'=>array('editable'=>true, 'unique'=>true, 'editrules'=>array('required'=>true)),
			'os_ids'=>array('editable'=>true),
			'groups_ids'=>array('editable'=>true)
		);
		if(!empty($this->params['container']) && $this->params['container'] == 'select_cart'){
			unset($this->options['list']);
			$this->options['list'] = array(
				'id', 
				'name'=>array('label'=>'TestCase Type Name'),
				'isactive'=>array('defval'=>1, 'hidden'=>true)
			);
		}
		$this->options['gridOptions']['label'] = 'Testcase Type';
		$this->options['linkTables'] = array('m2m'=>array('os', 'groups'=>array('link_table'=>'group_testcase_type', 'link_field'=>'group_id', 'self_link_field'=>'testcase_type_id', 'refer_table'=>'groups')));
    } 
	
	protected function _getLimit($params){
		$ret = array();
		//根据用户所在的group来确定testcase_type的可选择范围
		$this->tool->setDb($this->params['db']);
		$res = $this->tool->query("SELECT GROUP_CONCAT(distinct testcase_type_id) as testcase_type_ids FROM group_testcase_type WHERE group_id in ({$this->userInfo->group_ids})");
		$row = $res->fetch();
		$this->testcase_type_ids = $row['testcase_type_ids'];
		$ret[] = array('field'=>'xt.testcase_type.id', 'op'=>'in', 'value'=>$row['testcase_type_ids']);
// print_r($ret);		
		return $ret;
	}		
	
	public function accessMatrix(){
		// $access_matrix = parent::accessMatrix();
		$access_matrix['all']['all'] = false;
		$access_matrix['admin']['all'] = $access_matrix['assistant_admin']['all'] = true;
		$access_matrix['assistant_admin']['view_edit_edit'] = false;
		return $access_matrix;
	}
	
}
?>
