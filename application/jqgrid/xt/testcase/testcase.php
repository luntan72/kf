<?php
require_once('table_desc.php');

class xt_testcase extends table_desc{
	protected $prj_exist = false;
	protected $testcase_type_ids = '';
	protected $testcase_module_ids = '';
	protected $os_ids = '';
	protected $board_type_ids = '';
	protected $prj_ids = '';
	protected function init($params){
		parent::init($params);
		$db = $this->params['db'];
		$table = $this->params['table'];
		$this->options['linktype'] = 'infoLink_ver';
		$this->options['list'] = array('id'=>array('hidden'=>true), 
			'code'=>array('label'=>'Name'), 
			'summary', 
			'prj_id'=>array('label'=>'Project', 'hidden'=>true), 
			'testcase_type_id'=>array('label'=>'Type', 'editrules'=>array('required'=>true)), 
			'testcase_source_id'=>array('label'=>'Source', 'hidden'=>true), 
			'testcase_category_id'=>array('label'=>'Category'), 
			'testcase_testpoint_id'=>array('label'=>'Testpoint', 'hidden'=>true), 
			'testcase_module_id'=>array('label'=>'Module'), 
			'auto_level_id'=>array('label'=>'Auto Level'), 
			'testcase_priority_id'=>array('label'=>'Priority'),
			'auto_run_minutes'=>array('label'=>'Auto Run Minutes'),
			'manual_run_minutes'=>array('label'=>'Manual Run Minutes'),
			'ver_ids'=>array('label'=>'Versions', 'hidden'=>true, 'hidedlg'=>true, 'formatter'=>'text'),
			'owner_ids'=>array('label'=>'Owner', 'hidden'=>true),			
			'last_run'=>array('label'=>'Last Run Since'), 
			'command'=>array('hidden'=>true), 
			'isactive'
		);
		$this->options['query'] = array(
			'buttons'=>array(
				'query_new'=>array('label'=>'New', 'onclick'=>'XT.go("/jqgrid/jqgrid/newpage/1/oper/information/db/xt/table/testcase/element/0")', 'title'=>'Create New Testcase'),
				'query_import'=>array('label'=>'Upload', 'onclick'=>'xt.testcase.import()', 'title'=>'Import Testcase'),
				'query_report'=>array('label'=>'Report', 'title'=>'Generate Reports'),
			), 
			'normal'=>array('key'=>array('excluded'=>true),'testcase_type_id', 
				'testcase_module_id'=>array('type'=>'single_multi', 'init_type'=>'single',
					'single_multi'=>array('db'=>$db, 'table'=>'testcase_module', 'label'=>'Testcase Module')), 
				'os_id'=>array('label'=>'OS'), 'chip_id', 'board_type_id'=>array('label'=>'Board Type'), 
				'testcase_category_id', 
				'prj_id'=>array('label'=>'Project', 'type'=>'single_multi', 'init_type'=>'single', 
					'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'Project')), 
				'owner_id', 'isactive',
				'auto_level_id'=>array('label'=>'Auto Level', 'type'=>'checkbox', 'colspan'=>1, 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'auto_level', 'label'=>'Auto Level')),
				'testcase_priority_id'=>array('label'=>'Priority', 'colspan'=>2, 'value'=>'1,2,3')), 
			'advanced'=>array('key_fields'=>array('label'=>'Key Fields', 'excluded'=>true),
				'edit_status_id'=>array('label'=>'Status', 'colspan'=>1),'last_run')
		);
		if(in_array("visitor", $this->userInfo->roles)){
			$this->options['query'] = array(
				'normal'=>array('key'=>array('excluded'=>true),'testcase_type_id', 
						'testcase_module_id', 'os_id'=>array('label'=>'OS'), 'chip_id', 'board_type_id'=>array('label'=>'Board Type'), 
						'testcase_category_id', 'prj_id','owner_id', 'isactive',
						'auto_level_id'=>array('label'=>'Auto Level', 'type'=>'checkbox', 'colspan'=>1, 'init_type'=>'single', 'single_multi'=>array('db'=>$db, 'table'=>'auto_level', 'label'=>'Auto Level')),
						'testcase_priority_id'=>array('label'=>'Priority', 'colspan'=>2)), 
				'advanced'=>array('key_fields'=>array('label'=>'Key Fields', 'excluded'=>true),
					'edit_status_id'=>array('label'=>'Status', 'colspan'=>1),'last_run')
			);
		}
		if(isset($this->params['container'])){
			switch($this->params['container']){
				case 'div_case_add':
				case 'div_mcu_case_add_select':
					unset($this->options['query']['buttons']);
					$this->options['query']['normal'] = array('key'=>array('excluded'=>true),
						'prj_id'=>array('label'=>'Project', 'type'=>'single_multi', 'init_type'=>'single', 
							'single_multi'=>array('db'=>$db, 'table'=>'prj', 'label'=>'Project')), 
						'testcase_type_id'=>array('type'=>'single_multi', 'init_type'=>'single',
							'single_multi'=>array('db'=>$db, 'table'=>'testcase_type', 'label'=>'Testcase Type')), 
						'testcase_module_id'=>array('type'=>'single_multi', 'init_type'=>'single',
							'single_multi'=>array('db'=>$db, 'table'=>'testcase_module', 'label'=>'Testcase Module'), ),
						'testcase_category_id', 'isactive', 'testcase_priority_id'=>array('label'=>'Priority'), 
						'auto_level_id'=>array('label'=>'Auto Level', 'type'=>'checkbox', 'colspan'=>2)
					);
					$this->options['query']['advanced'] = array('edit_status_id'=>array('label'=>'Status', 'type'=>'checkbox'),
						'key_fields'=>array('label'=>'Key Fields', 'excluded'=>true));
					break;
				case 'div_stream_action':
					unset($this->options['query']['buttons']);
					$this->options['query']['normal'] = array('key'=>array('excluded'=>true), 
						'prj_id', 'testcase_type_id', 
						'testcase_module_id','auto_level_id'=>array('label'=>'Auto Level'),'testcase_priority_id'=>array('label'=>'Priority')
					);
					$this->options['query']['advanced'] = array( 'key_fields'=>array('label'=>'Key Fields', 'excluded'=>true), 
						'edit_status_id'=>array('label'=>'Status'), 'testcase_category_id','last_run', 'isactive');
					break;
				case 'select_cart':
					$this->options['query']['normal'] = array('key'=>array('label'=>'Keyword', 'excluded'=>true),
						'testcase_category_id', 'testcase_module_id', 'testcase_priority_id'=>array('label'=>'Priority'), 
						'auto_level_id'=>array('label'=>'Auto Level'),'isactive');
					$this->options['query']['advanced'] = array('key_fields'=>array('label'=>'Key Fields', 'excluded'=>true), 'edit_status_id'=>array('label'=>'Status'));
					unset($this->options['query']['buttons']);
					break;
			}
		}
		$this->options['edit'] = array('code'=>array('label'=>'Name'), 'summary', 'testcase_type_id', 'testcase_module_id', 'testcase_testpoint_id', 
				'testcase_category_id', 'testcase_source_id', 'isactive');
				
		$this->options['navOptions']['refresh'] = false;
		
		$this->parent_table = 'testcase_testpoint';
		$this->parent_field = 'testcase_testpoint_id';
	}
	
	public function getRowRole($table_name = '', $id = 0){
		$role = parent::getRowRole($table_name, $id);
		if(!empty($this->params['ver'])){
			$ver = $this->params['ver'];
			if(!is_numeric($ver) && is_string($ver)){
				$ver = json_decode($ver, true);
			}
			if(is_array($ver))
				$ver = $ver[0];
			if(is_numeric($ver)){
				$this->tool->setDb('xt');
				$res = $this->tool->query("SELECT * FROM testcase_ver WHERE id=$ver");
				if($row = $res->fetch()){
					$userId = $this->userInfo->id;
					if($row['edit_status_id'] != EDIT_STATUS_PUBLISHED && $row['edit_status_id'] != EDIT_STATUS_GOLDEN){
						if($row['updater_id'] == $userId)
							$role = array('row_owner');
					}
					elseif(in_array('tester', $this->userInfo->roles)){ //只有publish的Version可以编辑，从而生成一个新的Version
						$role = array('row_ver_newer');
					}
				}
			}
		}
		// if(empty($role)){
			// if(in_array('tester', $this->userInfo->roles)){ //只有publish的Version可以编辑，从而生成一个新的Version
				// $role = 'row_ver_newer';
			// }
		// }
// print_r("role = ".$role);		
		return $role;
	}

	public function accessMatrix(){
		// $access_matrix = array('tester'=>array('all'=>false));
		$access_matrix = parent::accessMatrix();
		$access_matrix['guest']['client_export'] = true;
		// $access_matrix['all']['all'] = false;
		$access_matrix['Dev'] = $access_matrix['normal'] = 
			array('all'=>false, 'index'=>true, 'query'=>true, 'list'=>true, 'information'=>true, 'update_information_page'=>true, 'tag'=>true);
		$access_matrix['tester']['view_edit_abort'] = $access_matrix['tester']['view_edit_ask2review'] = 
			$access_matrix['tester']['view_edit_publish'] = $access_matrix['tester']['view_edit_edit'] = false;
		$access_matrix['row_ver_newer'] = $access_matrix['tester'];
		$access_matrix['row_ver_newer']['view_edit_edit'] = true;
		return $access_matrix;
	}
	
	protected function _getLimit($params){
		$ret = array();
		//根据用户所在的group来确定testcase_type的可选择范围
		$this->tool->setDb('xt');
		$res = $this->tool->query("SELECT GROUP_CONCAT(distinct testcase_type_id) as testcase_type_ids FROM group_testcase_type WHERE group_id in ({$this->userInfo->group_ids})");
		$row = $res->fetch();
		$this->testcase_type_ids = $row['testcase_type_ids'];
		$ret[] = array('field'=>'testcase_type_id', 'op'=>'in', 'value'=>$row['testcase_type_ids']);
		//根据testcase_type来确定testcase_module的可选择范围
		$testcase_module_ids = array();
		if(!empty($this->testcase_type_ids)){
			$res = $this->tool->query("SELECT DISTINCT testcase_module_id from testcase_module_testcase_type WHERE testcase_type_id in ({$this->testcase_type_ids})");
			while($row = $res->fetch())
				$testcase_module_ids[] = $row['testcase_module_id'];
		}
		$this->testcase_module_ids = implode(',', $testcase_module_ids);
		$ret[] = array('field'=>'testcase_module_id', 'op'=>'in', 'value'=>$testcase_module_ids);
		//根据testcase_type来确定os的可选择范围
		$os_ids = array();
		if(!empty($this->testcase_type_ids)){
			$res = $this->tool->query("SELECT DISTINCT os_id from os_testcase_type WHERE testcase_type_id in ({$this->testcase_type_ids})");
			while($row = $res->fetch())
				$os_ids[] = $row['os_id'];
		}
		$this->os_ids = implode(',', $os_ids);
		$ret[] = array('field'=>'os_id', 'op'=>'in', 'value'=>$os_ids);
		// 根据os来确定Project以及相关的chip和board_type的选择范围
		$chip_ids = array();
		$board_type_ids = array();
		$prj_ids = array();
		if(!empty($os_ids)){
			$res = $this->tool->query("SELECT * FROM prj where os_id in (".implode(',', $os_ids).")");
			while($row = $res->fetch()){
				$chip_ids[] = $row['chip_id'];
				$board_type_ids[] = $row['board_type_id'];
				$prj_ids[] = $row['id'];
			}
			$chip_ids = array_unique($chip_ids);
			$board_type_ids = array_unique($board_type_ids);
			$prj_ids = array_unique($prj_ids);
		}
		$this->chip_ids = implode(',', $chip_ids);
		$this->board_type_ids = implode(',', $board_type_ids);
		$this->prj_ids = implode(',', $prj_ids);
		$this->fillOptionConditions['chip_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$chip_ids));
		$this->fillOptionConditions['prj_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$prj_ids));
		$this->fillOptionConditions['board_type_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$board_type_ids));
		return $ret;
	}
	
	protected function handleFillOptionCondition(){
		//根据用户所在的group来确定testcase_type的可选择范围
		$this->tool->setDb('xt');
		$res = $this->tool->query("SELECT GROUP_CONCAT(distinct testcase_type_id) as testcase_type_ids FROM group_testcase_type WHERE group_id in ({$this->userInfo->group_ids})");
		$row = $res->fetch();
		$this->testcase_type_ids = $row['testcase_type_ids'];
		$this->fillOptionConditions['testcase_type_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$row['testcase_type_ids']));
		//根据testcase_type来确定testcase_module的可选择范围
		$testcase_module_ids = array();
		if(!empty($this->testcase_type_ids)){
			$res = $this->tool->query("SELECT DISTINCT testcase_module_id from testcase_module_testcase_type WHERE testcase_type_id in ({$this->testcase_type_ids})");
			while($row = $res->fetch())
				$testcase_module_ids[] = $row['testcase_module_id'];
		}
		$this->testcase_module_ids = implode(',', $testcase_module_ids);
		$this->fillOptionConditions['testcase_module_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$testcase_module_ids));
		//根据testcase_type来确定os的可选择范围
		$os_ids = array();
		if(!empty($this->testcase_type_ids)){
			$res = $this->tool->query("SELECT DISTINCT os_id from os_testcase_type WHERE testcase_type_id in ({$this->testcase_type_ids})");
			while($row = $res->fetch())
				$os_ids[] = $row['os_id'];
		}
		$this->os_ids = implode(',', $os_ids);
		$this->fillOptionConditions['os_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$os_ids));
		// 根据os来确定Project以及相关的chip和board_type的选择范围
		$chip_ids = array();
		$board_type_ids = array();
		$prj_ids = array();
		if(!empty($os_ids)){
			$res = $this->tool->query("SELECT * FROM prj where os_id in (".implode(',', $os_ids).")");
			while($row = $res->fetch()){
				$chip_ids[] = $row['chip_id'];
				$board_type_ids[] = $row['board_type_id'];
				$prj_ids[] = $row['id'];
			}
			$chip_ids = array_unique($chip_ids);
			$board_type_ids = array_unique($board_type_ids);
			$prj_ids = array_unique($prj_ids);
		}
		$this->chip_ids = implode(',', $chip_ids);
		$this->board_type_ids = implode(',', $board_type_ids);
		$this->prj_ids = implode(',', $prj_ids);
		$this->fillOptionConditions['chip_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$chip_ids));
		$this->fillOptionConditions['prj_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$prj_ids));
		$this->fillOptionConditions['board_type_id'] = array(array('field'=>'id', 'op'=>'in', 'value'=>$board_type_ids));
	}

	protected function getQueryFields($params = array()){
//print_r($this->options['gridOptions']['colModel']);	
		parent::getQueryFields($params);
		if(!empty($this->options['query']['advanced']['key_fields'])){
			$this->options['query']['advanced']['key_fields']['edittype'] = 'checkbox';
			$this->options['query']['advanced']['key_fields']['cols'] = 6;
			$this->options['query']['advanced']['key_fields']['searchoptions']['value'] = 
				array('code'=>'Name', 'summary'=>'Summary', 'associated_code'=>"Associated Name", 'associated_summary'=>"Associated Summary", 'steps'=>'Steps', 'command'=>'Command');
			$this->options['query']['advanced']['key_fields']['queryoptions']['value'] = "code,summary";
		}
		if(!empty($this->options['query']['normal']['testcase_priority_id'])){
			$this->options['query']['normal']['testcase_priority_id']['edittype'] = 'checkbox';
			$this->options['query']['normal']['testcase_priority_id']['cols'] = 6;
			$this->options['query']['normal']['testcase_priority_id']['queryoptions']['value'] = TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3;
		}
		if(!empty($this->options['query']['advanced']['edit_status_id'])){
			$this->options['query']['advanced']['edit_status_id']['edittype'] = 'checkbox';
			$this->options['query']['advanced']['edit_status_id']['cols'] = 6;
			$this->options['query']['advanced']['edit_status_id']['queryoptions']['value'] = EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN;
			if(isset($this->params['container'])){
				if($this->params['container'] == 'div_case_add' || $this->params['container'] == 'div_mcu_case_add_select' || $this->params['container'] == 'div_stream_action'){
					$this->options['query']['advanced']['edit_status_id']['cols'] = 2;
					$this->options['query']['advanced']['edit_status_id']['searchoptions']['value'] = array(2=>'Golden', 1=>'Published');
					$this->options['query']['advanced']['edit_status_id']['queryoptions']['value'] = EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN;
				}
			}
		}
		if(!empty($this->params['container']) && ($this->params['container'] == 'div_case_add' || $this->params['container'] == 'div_mcu_case_add_select') && !empty($this->params['parent'])){
			$res = $this->db->query("select prj_ids, testcase_type_ids from cycle where id=".$this->params['parent']);
			if($row = $res->fetch()){
				$cart_data = new stdClass;
				$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$row['prj_ids'].'"}]}';
				$this->options['query']['normal']['prj_id']['single_multi']['data'] = json_encode($cart_data);
				$res0 = $this->db->query("select id, name from prj where id in (".$row['prj_ids'].")");
				$prj[0] = '';
				while($row0 = $res0->fetch())
					$prj[$row0['id']] = $row0['name'];
				if(!empty($this->options['query']['normal']['prj_id']))
					$this->options['query']['normal']['prj_id']['searchoptions']['value'] = $prj;
				$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$row['testcase_type_ids'].'"}]}';
				$this->options['query']['normal']['testcase_type_id']['single_multi']['data'] = json_encode($cart_data);
				$res0 = $this->db->query("select id, name from testcase_type where id in (".$row['testcase_type_ids'].")");
				$type[0] = '';
				while($row0 = $res0->fetch())
					$type[$row0['id']] = $row0['name'];
				if(!empty($this->options['query']['normal']['testcase_type_id']))
					$this->options['query']['normal']['testcase_type_id']['searchoptions']['value'] = $type;
			}
			
		}
		if(!empty($this->options['query']['advanced']['isactive']))
			$this->options['query']['advanced']['isactive']['queryoptions']['value'] = ISACTIVE_ACTIVE;
		if(!empty($this->options['query']['normal']['isactive']))
			$this->options['query']['normal']['isactive']['queryoptions']['value'] = ISACTIVE_ACTIVE;
		return $this->options['query'];
	}
	
	protected function getListFields($params = array()){
		return parent::getListFields($params);
	}
	
	public function calcSqlComponents($params, $limited = true){
		$type_exist = $module_exist = $os_exist = $chip_exist = $board_type_exist = $prj_exist = false;
		foreach($params['searchConditions'] as $item){
			if(is_array($item)){
				switch($item['field']){
					case 'testcase_type_id':
						$type_exist = true;
						break;
					case 'testcase_module_id':
						$module_exist = true;
						break;
					case 'os_id':
						$os_exist = true;
						break;
					case 'chip_id':
						$chip_exist = true;
						break;
					case 'board_type_id':
						$board_type_exist = true;
						break;
					case 'prj_id':
						$prj_exist = true;
						break;
				}
			}
		}
		if(!$type_exist)
			$params['searchConditions'][] = array('field'=>'testcase_type_id', 'op'=>'in', 'value'=>$this->testcase_type_ids);
		if(!$module_exist)
			$params['searchConditions'][] = array('field'=>'testcase_module_id', 'op'=>'in', 'value'=>$this->testcase_module_ids);
		if(!$os_exist)
			$params['searchConditions'][] = array('field'=>'os_id', 'op'=>'in', 'value'=>$this->os_ids);
		if(!$chip_exist)
			$params['searchConditions'][] = array('field'=>'chip_id', 'op'=>'in', 'value'=>$this->chip_ids);
		if(!$board_type_exist)
			$params['searchConditions'][] = array('field'=>'board_type_id', 'op'=>'in', 'value'=>$this->board_type_ids);
		if(!$prj_exist)
			$params['searchConditions'][] = array('field'=>'prj_id', 'op'=>'in', 'value'=>$this->prj_ids);
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
		return $components;
	}
	
	public function getMoreInfoForRow($row){
		if(!empty($row['linked_ver_ids']))
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
		$prj_id = false;
		$prj_filter = array();
		$prj_where = '1';
		$prj_ids = array();
		$prj_empty = false;
		foreach($special as $c){
			if($c['field'] == "key")
				$key_value = $c['value'];
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

	protected function getButtons(){
        $buttons = array(
            'link2prj'=>array('caption'=>'Link to Projects',
                'buttonimg'=>'',
                'title'=>'Link to Projects or Drop from Projects'),
			'unlinkfromprj'=>array('caption'=>'Unlink From Projects'),
			'publish'=>array('caption'=>'Publish'),
			'change_owner'=>array('caption'=>'Change Owner', 'buttonimg'=>'', 'title'=>'Change the owner for the selected items'),
			'coversrs'=>array('caption'=>'Cover SRS')
			
//			'batch_edit'=>array('caption'=>'Batch Edit', 'title'=>'Batch Edit'),
        );
        $buttons = array_merge($buttons, parent::getButtons());
		unset($buttons['add']);
		return $buttons;
	}
}
?>