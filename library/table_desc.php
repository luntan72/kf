<?php
require_once('kf_object.php');
require_once('dbfactory.php');
require_once('toolfactory.php');
require_once('useradminfactory.php');
require_once('lang.php');

class table_desc extends kf_object{
	protected $configed = array();
	protected $colModelMap = array();
	protected $userAdmin = null;
	protected $userInfo = null;
	protected $colModels = array();
	
	protected $limited = false; //是否已经进行limit计算
	protected $limit = array();	//实际的限制条件，主要就是id的选取范围
	
	//以下的各种表间关系可能组合出现，所以不适合用继承来实现
	protected $standardLinkTabled = false;
	protected $linkTables = array();	//各种表间关系，如对多对关系（通过link表互相关联), 多属性（如一个人有多个通讯方式 ）
	
	protected $blankItems = array();	// 哪些字段需要==BLANK==选项
	protected $allFields = array();		//哪些字段需要得到全部信息
	
	protected function init($params){
		if($params['table'] == 'user'){
			$params['table'] = 'users';
		// print_r("THIS IS AN ERROR");	
			// print_r($this->params);
			// return;
		}		
		if($params['table'] == 'group')
			$params['table'] = 'groups';
		
// print_r("IT is a test");		
		parent::init($params);
		$this->options['db'] = $this->params['db'];
		$this->options['table'] = $this->params['table'];
		
		$this->tool = toolFactory::get(array('db'=>$params['db']));
		$db = $this->tool->get_db_handle();
		$this->params['real_db'] = $db->get('real_db_name');
		$this->params['real_table'] = $params['table'];
		$this->userAdmin = useradminFactory::get();
		$this->userInfo = $this->userAdmin->getUserInfo();
		$this->tool->setDb($this->params['db']);

        if (!empty($params['filters'])){
            $json_filters = json_decode($params['filters'], true);
            if(is_array($json_filters)){
				$gopr = strtolower($json_filters['groupOp']);
				$rules = $json_filters['rules'];
				$this->params['searchConditions'] = $this->tool->generateFilterConditions($rules);
				foreach($this->params['searchConditions'] as $k=>$cond){
					$this->params['condMap'][$cond['field']] = $cond;
				}
			}
		}
		$this->_setSubGrid();
		if(!empty($this->options['gridOptions']['subGrid'])){
			if(!empty($this->options['condMap'])){
				foreach($this->options['condMap'] as $condMap)
					$this->options['subGrid']['additional'][$condMap['field']] = $condMap['value'];
			}
		}
	// print_r($this->params);	
	}

	public function post_init(){
		//设置一些默认值
		if(empty($this->options['caption']))
			$this->options['caption'] = ucwords(implode(' ', explode('_', $this->params['table'])));
		
		// 标准化表间关系
		$this->standardLinkTable();
	}
	
	protected function _setSubGrid(){
		
	}
	
	protected function standardLinkTable(){
		if(!$this->standardLinkTabled && !empty($this->options['linkTables'])){
			$linkTables = array();
			$this->standardLinkTabled = true;
			foreach($this->options['linkTables'] as $rel=>$relData){
				if(is_int($rel)){ // set it as m2m
					$rel = 'm2m';
				}
// print_r($relData);			
				if(!is_array($relData)){
					$relData = array('table'=>$relData);
				}
				foreach($relData as $key=>$linkInfo){//rel: one2one, one2m, m2m, ver, history, treeview
					if(is_string($linkInfo)){ // should be the table name
						$linkInfo = array('table'=>$linkInfo);
					}
					elseif(empty($linkInfo['table']) && !is_int($key)){
						$linkInfo['table'] = $key;
					}
					$tmp = explode(".", $linkInfo['table']);
					if(count($tmp) == 2){
						$linkInfo['db'] = $tmp[0];
						$linkInfo['table'] = $tmp[1];
					}
					if(empty($linkInfo['db']))
						$linkInfo['db'] = $this->get('db');
					if(empty($linkInfo['self_link_field']))
						$linkInfo['self_link_field'] = $this->get('real_table').'_id';
					if(empty($linkInfo['link_db']))
						$linkInfo['link_db'] = $this->get('db');
					if($rel == 'm2m' || $rel == 'node_ver_m2m'){
						if(empty($linkInfo['link_table'])){
							$tables = array($this->get('table'));
							$tables[] = $linkInfo['table'];
							sort($tables);
							$linkInfo['link_table'] = implode('_', $tables);
						}
						if(empty($linkInfo['link_field']))
							$linkInfo['link_field'] = $linkInfo['table'].'_id';
						if($rel == 'node_ver_m2m'){
							$node_table = "";
							if(substr($this->get('table'), -4) == '_ver')
								$node_table = substr($this->get('table'), 0, -4);
							if(empty($linkInfo['node_field'])){
								if(!empty($node_table))
									$linkInfo['node_field'] = $node_table.'_id';
							}
							if(empty($linkInfo['link_node_field'])){
								if(!empty($node_table))
									$linkInfo['link_node_field'] = $node_table.'_id';
							}
						}
					}
					elseif($rel == 'one2m'){
						if(empty($linkInfo['link_table']))
							$linkInfo['link_table'] = $linkInfo['table'];
						if(empty($linkInfo['link_field'])){
							
						}
					}
					elseif($rel == 'ver'){
						if(empty($linkInfo['ver_field']))
							$linkInfo['ver_field'] = 'ver';
					}
					elseif($rel == 'treeview'){
						if(empty($linkInfo['tree_table'])){
							preg_match('/^(.*)_node$/', $linkInfo['table'], $matches);
							if(!empty($matches))
								$node = $matches[1];
							else	
								$node = $linkInfo['table'];
							$linkInfo['tree_table'] = $node.'_tree';
						}
					}
					if(!empty($relData['real_table']))
						$linkInfo['real_table'] = $relData['real_table'];
					dbFactory::get($linkInfo['db'], $real_db);
					$linkInfo['real_db'] = $real_db; //需要切换成真实的db
					if(!empty($linkInfo['link_db'])){
						dbFactory::get($linkInfo['link_db'], $real_db);
						$linkInfo['real_link_db'] = $real_db; //需要切换成真实的db
					}
					$linkTables[$rel]["{$linkInfo['db']}.{$linkInfo['table']}"] = $linkInfo;
				}
			}
			unset($this->options['linkTables']);
			$this->options['linkTables'] = $linkTables;
// print_r("db ={$this->options['db']}, table = {$this->options['table']}\n");		
// print_r($this->options['linkTables']);
		}
	}

	public function getLinkTables(){
		$this->standardLinkTable();
		return empty($this->options['linkTables']) ? array() : $this->options['linkTables'];
	}
	
	public function getCaption(){
		return $this->options['caption'];
	}
	
	public function getOptions($trimed = true, $params = array()){
// $this->tool->p_t("Before table_desc getOptions");
// print_r($params);
// print_r($this->options);	
		$this->config($trimed, $params);
// print_r($this->options['add']);
// $this->tool->p_t("Before table_desc getOptions");
		return $this->options;
	}

	//可以根据当前的Action进行不同的配置
	protected function config($trimed = true, $params){
		$action_name = $this->params['action_name'] == 'none' ? (empty($this->params['oper']) ? 'none' : $this->params['oper']) : $this->params['action_name'];
// print_r("Action = $action_name\n");
		if (!empty($this->configed[$action_name]))
			return;
		$this->configed[$action_name] = true;

		if(!in_array($action_name, array('index', 'getGridOptions', 'list', 'information', 'update_information_page', 'linkage', 'refreshcell')))
			return;
// print_r("Action = $action_name\n");
		if (empty($this->options['list']))
			$this->options['list'] = '*';
		$this->options['list'] = $this->standardColumns($this->options['list']);
// print_r($this->options['list']);		
// print_r($this->colModels);
		
		switch($action_name){
			case 'index':
			case 'getGridOptions':
			case 'list':
			case 'linkage':
				$this->configForList($action_name);
				break;
			case 'information':
			case 'update_information_page':
			case 'refreshcell':
				$this->configForInfo($action_name);
				break;
			default:
				break;
		}
// if($this->get('table') == 'testcase_ver')
// print_r("<<<<<<<<<<<<<<<<< line = ".__LINE__);		
		
		$this->options['buttons'] = $this->getButtons(); //为不同的Action提供不同的Buttons，这样，information里的view_edit_buttons就集成到这一边
// if($this->get('table') == 'testcase_ver')
// print_r("++++++++1 line = ".__LINE__);	
		if(!empty($this->params['self_action'])){	
			$this->options['buttons'] = $this->params['self_action']->trimButtons($this->options['buttons']);
	// if($this->get('table') == 'testcase_ver')
	// print_r("++++++++2 line = ".__LINE__);		
			if(!empty($this->options['query']['buttons'])){
				$this->options['query']['buttons'] = $this->params['self_action']->trimButtons($this->options['query']['buttons']);
			}
		}
// if($this->get('table') == 'testcase_ver')
// print_r("++++++++3 line = ".__LINE__);		
	}
	
	protected function configForList($action_name){
// print_r("start configForList-----\n");		
		$this->options['contextMenuItems'] = $this->contextMenu();
		$this->options['list'] = $this->getListFields();
		if(in_array($action_name, array('index', 'getGridOptions')))
			$this->fillOptions('list');
		foreach($this->options['list'] as $field=>$model){
			$this->options['gridOptions']['colModel'][] = $model;
			$current = count($this->colModelMap);
			$this->colModels[$current] = $model;
			$this->colModelMap[$field] = $current;
		}
		$this->options['gridOptions']['colModel'] = $this->trimColModel($this->colModels);
		$this->options['gridOptions']['colModelMap'] = $this->colModelMap;
		
		if(!empty($this->options['query'])){
			$this->options['query'] = $this->getQueryFields();
			$this->fillOptions('query');
		}
// print_r("configForList+++++++\n");		
// print_r($this->options);		
	}
	
	protected function configForInfo($action_name){
		if(empty($this->params['display_status'])){
			if(!empty($this->params['id']) && $this->params['id'] != 0 && $this->params['id'] != '0')
				$this->params['display_status'] = DISPLAY_STATUS_VIEW;
			else
				$this->params['display_status'] = DISPLAY_STATUS_NEW;
		}
// print_r($this->params['display_status']);	
		if(empty($this->params['id']))
			$this->params['display_status'] = DISPLAY_STATUS_NEW;
		$idx = 'view';
// print_r(">>>id = {$this->params['id']}, id = $id, table = {$this->params['table']}, display_status = ".$this->params['display_status']);
		switch($this->params['display_status']){
			case DISPLAY_STATUS_VIEW:
				$this->options['view'] = $this->getViewFields();
				break;
			case DISPLAY_STATUS_NEW:
				$this->options['add'] = $this->getAddFields();
				$idx = 'add';
				break;
			case DISPLAY_STATUS_EDIT:
				$this->options['edit'] = $this->getEditFields();
				$idx = 'edit';
				break;
		}
		$this->fillOptions($idx);
	}
	
    protected function contextMenu(){
		return array();
        // $menu = array();
        // $menu['information'] = 'information';
        // $menu['export'] = 'export';
        // return $menu;
    }
	
	protected function getButtons(){
		$buttons = array();
		switch($this->params['action_name']){
			case 'list':
			case 'index':
			case 'getGridOptions':
				$buttons = $this->getButtonForList();
				break;
			case 'information':
			case 'update_information_page':
				$buttons = $this->getButtonForInfo();
				break;
			default:
		}
		return $buttons;
	}
	
	protected function getButtonForList(){
		$buttons = array();
		$buttons['add'] = array('caption'=>g_str('add'));
		// if (count($this->options['gridOptions']['colModel']) > 5)
			$buttons['columns'] = array(
				'caption'=>g_str('columns'),
                'buttonimg'=>'',
                'title'=>'Show/Hide Columns',
            );
		$buttons['subscribe'] = array('caption'=>g_str('subscribe'), 'buttonimg'=>'', 'title'=>'Subscribe the selected records');
        $buttons['export'] = array(
			'caption'=>g_str('export'),
			'buttonimg'=>'',
			'title'=>'Export for selected records',
		);
		// if(isset($this->options['tags'])){
		if($this->tool->tableExist('tag', $this->params['db'])){
			$buttons['tag'] = array(
				'caption'=>g_str('tag'),
				'buttonimg'=>'',
				'title'=>'Create a Tag for selected records',
			);
			$buttons['removeFromTag'] = array(
				'caption'=>g_str('removeFromTag'),
				'buttonimg'=>'',
				'title'=>'Remove selected records From This Tag',
			);
		}
		// check the special fields
		foreach($this->options['gridOptions']['colModel'] as $model){
			switch($model['name']){
				case 'used_by_id':
					$buttons['lend'] = array('caption'=>g_str('lend'), 'buttonimg'=>'', 'title'=>'Lend the selected items');
					break;
				case 'owner_id':
					$buttons['change_owner'] = array('caption'=>g_str('change_owner'), 'buttonimg'=>'', 'title'=>'Change the owner for the selected items');
					break;
				case 'isactive':
					$buttons['activate'] = array('caption'=>g_str('activate'), 'buttonimg'=>'', 'title'=>'Activate the selected items');
					$buttons['inactivate'] = array('caption'=>g_str('inactivate'), 'buttonimg'=>'', 'title'=>'Inactivate the selected items');
					break;
			}
		}
		//检查表名是否*_ver或*_history，如果是，则默认添加ver_diff
		if(!empty($this->options['version'])){ //该表需要版本管理
			$buttons['ver_diff'] = array('caption'=>g_str('ver_diff'));//Diff the versions');
		}
		if(!empty($this->options['history'])){ //该表需要版本管理
			$buttons['his_diff'] = array('caption'=>g_str('his_diff')); //'Diff the history');
		}
        return $buttons;
	}
			
	protected function getButtonForInfo(){
		$right_style = 'position:relative;float:right';
		$left_style = 'position:relative;float:left';
		$left_display = $left_style;
		$left_hide = $left_style.';display:none';
		$right_display = $right_style;
		$right_hide = $right_style.';display:none';
		
		$view_buttons = array(
			'view_edit_cancel'=>array('label'=>g_str('cancel'), 'style'=>$right_display),
			'view_edit_save'=>array('label'=>g_str('save'), 'style'=>$right_display),
			'view_edit_saveandnew'=>array('label'=>g_str('saveandnew'), 'style'=>$right_display),
			'view_edit_cloneit'=>array('label'=>g_str('clone'), 'style'=>$right_display),
			'view_edit_edit'=>array('label'=>g_str('edit'), 'style'=>$right_display),
			'view_edit_abort'=>array('label'=>g_str('abort'), 'style'=>$right_display),
			'view_edit_ask2review'=>array('label'=>g_str('asktoreview'), 'style'=>$left_display),
			'view_edit_publish'=>array('label'=>g_str('publish'), 'style'=>$left_display),
		);
// print_r("id = {$this->params['id']}, displayStatus = {$this->params['display_status']}");		
		if(empty($this->params['id'])){
			unset($view_buttons['view_edit_cloneit']);
			unset($view_buttons['view_edit_edit']);
			unset($view_buttons['view_edit_abort']);
			unset($view_buttons['view_edit_ask2review']);
			unset($view_buttons['view_edit_publish']);
		}
		if(empty($this->params['version']) || empty($this->params['ver'])){
			unset($view_buttons['view_edit_ask2review']);
			unset($view_buttons['view_edit_publish']);
			unset($view_buttons['view_edit_abort']);
		}
		elseif(!empty($this->params['id'])){ // version and not empty, 应根据当前edit_status_id来确定buttons
			if(is_string($this->params['id']))
				$this->params['id'] = explode(',', $this->params['id']);
			if(count($this->params['id']) == 1){//单个编辑
				$res = $this->tool->query("SELECT edit_status_id FROM {$linkInfo['table']} where id={$params['ver']}");
				if($row = $res->fetch()){
					switch($row['edit_status_id']){
						case EDIT_STATUS_PUBLISHED:
						case EDIT_STATUS_GOLDEN:
							unset($view_buttons['view_edit_ask2review']);
							unset($view_buttons['view_edit_publish']);
							unset($view_buttons['view_edit_abort']);
							break;
					}
				}
				else{ //没有该版本
					
				}
			}
			else{ //批处理
				
			}
		}
// print_r($view_buttons);		
		if($this->params['display_status'] == DISPLAY_STATUS_VIEW){
			unset($view_buttons['view_edit_cancel']);
			unset($view_buttons['view_edit_save']);
			unset($view_buttons['view_edit_saveandnew']);
		}
		else{
			unset($view_buttons['view_edit_edit']);
			unset($view_buttons['view_edit_cloneit']);
		}
// print_r($view_buttons);		
		return $view_buttons;
	}
	
	// public function fetch_tags(){
		// $tags = array();
		// if($this->tool->tableExist('tag', $this->params['db'])){
			// $userList = $this->userAdmin->getUserList();
			// $userList[0] = 'Unknown';
			// $base_sql = "SELECT id, name, creater_id FROM tag ".
				// " WHERE `db_table`='{$this->params['db']}.{$this->params['table']}'";
			// if(!empty($this->userInfo->id)){
				// $sql = $base_sql . " and creater_id={$this->userInfo->id} ORDER BY name ASC";
				// $res = $this->tool->query($sql);
				// while($row = $res->fetch()){
		// // print_r($row);		
					// $row['name'] = $row['name'].' (--By '.$userList[$row['creater_id']].')';
					// $tags[] = $row;
				// }
			// }
			// $sql = $base_sql . " and `public`=1";
			// if (!empty($this->userInfo->id))
				// $sql .= " AND creater_id!={$this->userInfo->id}";
			// $sql .= " ORDER BY name ASC";
			// $res = $this->tool->query($sql);
			// while($row = $res->fetch()){
	// //print_r($row);		
				// $row['name'] = $row['name'].' (--By '.$userList[$row['creater_id']].')';
				// $tags[] = $row;
			// }
		// }
		// return $tags;
	// }
	
	//确定自己的最大范围，返回FALSE表示没有限制
	public function getLimit($params = array()){
		if(empty($this->limited)){
			$this->limited = true;
			$this->limit = $this->_getLimit($params);
		}
		return $this->limit;
	}
	
	protected function _getLimit($params){ //主要是指定一些搜索限制，比如，某个角色只能看某些内容
		$ret = array();
		return $ret;
		
		foreach($this->options['list'] as $field=>$e){
			if(!empty($e['searchConditions'])){
				foreach($e['searchConditions'] as $e){
					$f = $e['field'];
					$temp = explode('.', $f);
					switch(count($temp)){
						case 2:
							$e['field'] = $this->params['db'].'.'.$e['field'];
							break;
						case 1:
							$e['field'] = $this->params['db'].'.'.$this->params['table'].'.'.$e['field'];
							break;
					}
					$ret[] = $e;
				}
			}
			elseif(!empty($e['data_source_db']) && !empty($e['data_source_table'])){
				$t = tableDescFactory::get($e['data_source_db'], $e['data_source_table'], array('action_name'=>'list'));
				$t->getOptions();
				$limited = $t->getLimit(array());
// print_r("+++table = ".$e['data_source_table']."\n");
// print_r($limited);						
				if(!empty($limited)){
					$limited['db'] = $e['data_source_db'];
					$limited['table'] = $e['data_source_table'];
// print_r("=========");					
// print_r($limited);					
					
					$list_action = actionFactory::get(null, 'list', $limited);
					$list = $list_action->getList();
					$ids = array();
					foreach($list as $e){
						$ids[] = $e['id'];
					}
					$ret[] = array('field'=>$field, 'op'=>'in', 'value'=>implode(',', $ids));//$list);
				}
			}
		}
		return $ret;
	}
	
	protected function getOptionData($column, $display_status){
		$needStrictLimit = false;
		if(!empty($column['data_source_table']) || !empty($column['data_source_sql'])){
			if($this->tool->tableExist($column['data_source_table'], $column['data_source_db'])){
				if(empty($column['filled']) && (!isset($this->params['fill']) || $this->params['fill'] == true)){
// $this->tool->p_t("start to getOptionData: {$column['data_source_table']}, column = {$column['name']}");
					$nameKey = isset($column['nameKey']) ? $column['nameKey'] : false;
					$t = tableDescFactory::get($column['data_source_db'], $column['data_source_table'], array('action_name'=>'list'));
					$column['searchConditions'] = $t->getLimit($this->params);
					$displayField = $t->getDisplayField();
// $this->tool->p_t("finish to get table_desc: {$column['data_source_table']}");
					if($needStrictLimit){
						$list_action = actionFactory::get(null, 'list', array('db'=>$column['data_source_db'], 'table'=>$column['data_source_table'], 'sidx'=>$displayField, 'sord'=>'asc'));
						$list = $list_action->getList();
	// print_r($list);						
						$options = array();
						// if (!empty($column['blank']))
							$options[0] = '';
						if (!empty($column['blank_item']))
							$options[-1] = '==Blank==';
						
						foreach($list as $e)
							$options[$e['id']] = $e;
						
						if (empty($options)){
							$column['edittype'] = $column['stype'] = 'text';
						}
						else{
							if ($nameKey){
								foreach($options as $key=>$option){
									unset($options[$key]);
									if(is_array($option)){
										$options[$displayField] = $option;
									}
									else{
										$options[$option] = $option;
									}
								}
							}
							$searchOptionsValue = $this->tool->array2Str($options);
							
							// unset($options[0]);
							unset($options[-1]);
							$formatOptionsValue = $this->tool->array2Str($options);
							
							$addoptions = array();
							foreach($options as $id=>$item){
								if(!isset($item['isactive']) || $item['isactive'] == ISACTIVE_ACTIVE || $item['isactive'] == 0)
									$addoptions[$id] = $item;
							}
							if (empty($column['addoptions']['value'])){
		// print_r(">>>>>>>>>{$columnDef['name']}>>>>>>>>>>>>>>>");						
								$column['addoptions']['value'] = $addoptions;
							}
							if (empty($column['editoptions']['value'])){
								$column['editoptions']['value'] = $options;
							}
	// print_r($column['editoptions']);							
							if (empty($column['formatoptions']['value'])){
								$column['formatoptions']['value'] = $formatOptionsValue;
							}
							if (empty($column['searchoptions']['value'])){
								$column['searchoptions']['value'] = $searchOptionsValue;
							}
							if (!empty($column['stype']) && $column['stype'] == 'select' && empty($column['searchoptions']['value'])){
								$column['searchoptions']['value'] = $searchOptionsValue;
			//                    $columnDef['searchoptions']['dataUrl'] = "/jqgrid/jqgrid/oper/getSelectList/db/$db/table/$table";
							}						
						}
					}
					else
						$this->tool->fillOptions($column, $display_status);
					$column['filled'] = true;
// $this->tool->p_t("finish to getOptionData: {$column['data_source_table']}");
					
				}
				else
					$column['notfill'] = true;
			}		
		}	
		return $column;
	}
	
	protected function fillOptions($idx){
		switch($idx){
			case 'query':
				$display_status = DISPLAY_STATUS_QUERY;
				break;
			case 'view':
				$display_status = DISPLAY_STATUS_VIEW;
				break;
			case 'add':
				$display_status = DISPLAY_STATUS_NEW;
				break;
			case 'edit':
				$display_status = DISPLAY_STATUS_EDIT;
				break;
			case 'list':
				$display_status = DISPLAY_STATUS_LIST;
				break;
		}
		if($idx == 'query'){
			foreach($this->options[$idx]['normal'] as $field=>&$column){
				$column = $this->getOptionData($column, $display_status);
			}
			if(isset($this->options[$idx]['advanced'])){
				foreach($this->options[$idx]['normal'] as $field=>&$column){
					$column = $this->getOptionData($column, $display_status);
				}
			}
		}
		else{
			foreach($this->options[$idx] as $field=>&$column){
				$column = $this->getOptionData($column, $display_status);
			}
		}
	}
	
	protected function getFieldModel($field){
		$model = array();
		if (isset($this->colModelMap[$field])){
			$model = $this->colModels[$this->colModelMap[$field]];
		}
		return $model;
	}
	
	protected function getListFields($params = array()){
		if(empty($params))
			$params = $this->params;
		$this->options['list'] = $this->modelFields($this->options['list'], $params);
		foreach($this->options['list'] as $field=>&$column){
			if(!empty($column['data_source_table']) || !empty($column['data_source_sql'])){
	// print_r("table = ".$column['data_source_table']);					
				if(empty($column['data_source_db']))
					$column['data_source_db'] = $this->params['db'];

				if(empty($column['formatoptions']['db']))
					$column['formatoptions']['db'] = $column['data_source_db'];
				if(empty($column['formatoptions']['table']))
					$column['formatoptions']['table'] = $column['data_source_table'];
				if(!empty($column['formatoptions']['db'])){
					dbFactory::get($column['formatoptions']['db'], $r_db);
					$column['formatoptions']['db'] = $r_db;
				}
			}
		}
// print_r($this->options['list']);		
		return $this->options['list'];
	}
	
	protected function getQueryFields($params = array()){
		if(empty($params))
			$params = $this->params;
		foreach($this->options['query'] as $k=>$v){ // options['query'] = array('buttons'=>$buttons, 'normal'=>$normal, 'advanced'=>$advanced)
			if ($k != 'buttons' && $k != 'cols'){
				$this->options['query'][$k] = $this->modelFields($v, $params);
				foreach($this->options['query'][$k] as $field=>&$prop){
					$prop['editrules']['required'] = false;
					$prop['unique'] = false;
// if($field == 'testcase_module_id')			
	// print_r($prop['single_multi']);
					
				}
			}
		}
// print_r($this->options['query']['normal']['testcase_module_id']['single_multi']);		
		return $this->options['query'];
	}
	
	protected function modelFields($fields, $params){
		$list = array();
		if (is_string($fields))
			$fields = explode(',', $fields);
// if($this->get('table') == 'testcase_ver')
// print_r($fields);		
		foreach($fields as $field=>$prop){
			if (is_int($field)){
				$field = $prop;
				$prop = array();
			}
			if (isset($this->colModelMap[$field])){
				$column = $this->tool->array_extends($this->colModels[$this->colModelMap[$field]], $prop);
			}
			else{
				if(isset($this->options['list'][$field])) // 只有list经过了StandardColumn处理
					$prop = $this->tool->array_extends($this->options['list'][$field], $prop);
				$column = $this->singleColModel($field, $prop, $params);
				//处理fillOptions
				// $current = count($this->colModelMap);
				// $this->colModels[$current] = $column;
				// $this->colModelMap[$field] = $current;
			}
			//如果params['searchConditions']里有关于该Column的限制，应加入
			if(!empty($this->params['condMap'])){
// print_r($this->params['condMap']);			
				if(!empty($this->params['condMap'][$field])){
					$column['defval'] = $column['editoptions']['defaultValue'] = $this->options['queryValue'][$field] = $this->params['condMap'][$field]['value'];
					$column['force_readonly'] = true;
				}
			}
// if($this->get('table') == 'testcase_ver'){
// print_r($column);		
// print_r("<br>");
// }
			$list[$field] = $column;
		}
		// $this->options['gridOptions']['colModelMap'] = $this->colModelMap;
		
		return $list;
	}
	
	protected function getViewFields($params = array()){
		if(empty($params))
			$params = $this->params;
//print_r($this->options['edit']);	
		if (empty($this->options['view'])){
			$this->options['view'] = isset($this->options['edit'])? $this->options['edit'] : $this->options['list'];
			// unset($list['created']);
			// unset($list['updated']);
			// unset($list['modified']);
			// unset($list['creater_id']);
// print_r($list);			
			// foreach($list as $id=>$e){
				// if(is_int($id))
					// $this->options['view'][] = $e;
				// else
					// $this->options['view'][$id] = $e;
			// }
			// $this->options['view'] = array_keys($list);
		}
// print_r($this->options['view']);		
		$this->options['view'] = $this->modelFields($this->options['view'], $params);
		unset($this->options['view']['id']);
// print_r($this->options['view']);		
		return $this->options['view'];
	}
	
	protected function getEditFields($params = array()){
		if(empty($params))
			$params = $this->params;
//print_r($this->options['edit']);	
		if (empty($this->options['edit'])){
			$list = $this->options['list'];
			unset($list['created']);
			unset($list['updated']);
			unset($list['modified']);
			unset($list['creater_id']);
			
			$this->options['edit'] = $list;
		}
		$this->options['edit'] = $this->modelFields($this->options['edit'], $params);
		unset($this->options['edit']['id']);
// if($this->params['table'] == 'testcase_ver')		
// print_r($this->options['edit']);		
		return $this->options['edit'];
	}
	
	protected function getAddFields($params = array()){
		if(empty($params))
			$params = $this->params;
// print_r($this->options['add']);		
		if (empty($this->options['add'])){
			if(!empty($this->options['edit']))
				$this->options['add'] = $this->options['edit'];
			else{
				$list = $this->options['list'];
				unset($list['created']);
				unset($list['updated']);
				unset($list['modified']);
				unset($list['creater_id']);
				$this->options['add'] = $list;
			}
		}
// print_r($this->options['add']);
		$this->options['add'] = $this->modelFields($this->options['add'], $params);
		unset($this->options['add']['id']);
		return $this->options['add'];
	}
	
    public function standardColumns($columns){
        if (is_string($columns))
            $columns = explode(',', $columns);
		$existFields = array();
		$star = false;
        foreach($columns as $key=>$existField){
            if (is_int($key))
				$key = $existField;
			if ($key !== '*')
				$existFields[] = $key;
			else
				$star = true;
        }
// print_r($this->params);		
		$descFields = $this->tool->describe($this->params['table'], $this->params['db']);

        $allColumns = array();
        $descs = array();
		$default = array('LENGTH'=>20, 'DATA_TYPE'=>'text', 'sortable'=>false);
        foreach($columns as $key=>$column){
			if (is_int($key)){
				$key = $column;
				$column = array();//'COLUMN_NAME'=>$key, 'name'=>$key);
			}
			
			if ($key != '*'){
				if (isset($descFields[$key]))
					$allColumns[$key] = array_merge($descFields[$key], $column);
				else{
					$allColumns[$key] = array_merge($default, $column);
				}
				// $this->colModel[$key] = $allColumns[$key];
			}
			else{
				foreach($descFields as $i=>$f){
					if (!in_array($i, $existFields)){
						$allColumns[$i] = array_merge($f, $column);
						// $this->colModel[$i] = $allColumns[$i];
					}
				}
			}
        }
        return $allColumns;
    }
    
	public function trimColModel($colModel){
		$trimed = array();
		$colMap = array();
		$container = isset($this->params['container']) ? $this->params['container'] : 'mainContent';
        $params = array('user_id'=>$this->userInfo->id, 'name'=>$this->params['db'].'_'.$this->params['table']);
		$cookie = json_decode($this->userAdmin->getCookie($params));
		if (!empty($cookie->rowNum))
			$rowNum = json_decode($cookie->rowNum);
        if (isset($rowNum->rowNum))
            $this->options['gridOptions']['rowNum'] = $rowNum->rowNum;
		if (isset($cookie->display))
			$display = json_decode($cookie->display);
		else
			$display = new stdClass();
        $maxOrder = count((array)$display);
//print_r($display);		
		$notMatch = true;
        if ($maxOrder == count($colModel)){ // cookie exists
			$notMatch = false;
            $tmp = array();
            foreach($colModel as $key=>$columnDef){
                if (isset($display->$columnDef['name'])){
                    $columnDef['hidden'] = $display->$columnDef['name']->hidden;
                    $columnDef['width'] = $display->$columnDef['name']->width;
					$order = $display->$columnDef['name']->order;
					$tmp[$order] = $columnDef;
//                    $columnDef['order'] = $display->$columnDef['name']->order;
                }
                else{
					$notMatch = true;
					break;
//                    $columnDef['order'] = $maxOrder ++;
                }
//                $tmp[$columnDef['order']] = $columnDef;
            }
            // now reset the colmodels
//print_r($maxOrder);
//print_r($tmp);            
			if (!$notMatch){
				for($i = 0; $i < $maxOrder; $i ++){
					if (isset($tmp[$i])){
						$trimed[$i] = $tmp[$i];
						$colMap[$tmp[$i]['name']] = $i;
					}						
					else{
						$notMatch = true;
						break;
					}
				}
			}
        }
		if ($notMatch)
            $trimed = $colModel;	
		else
			$this->colModelMap = $colMap;
			
		return $trimed;
	}
	
	public function getDisplayField(){
		if (empty($this->options['displayField'])){
			$desc = $this->tool->describe($this->get('table'), $this->get('db'));
			$this->options['displayField'] = $this->tool->getDisplayField($desc);
		}
		return $this->options['displayField'];
	}
	
	public function getRowRole($table_name = '', $id = 0){
// print_r("table_name = $table_name, id = $id ____________");
		$roles = array();
		$row = $this->getRowForRole($table_name, $id);
		if(!empty($row)){
			$matrix = $this->getRowRoleMatrix($row);
			foreach($matrix as $field=>$role){
				if(isset($row[$field]) && $row[$field] == $this->userInfo->id){
					$roles[] = $role;
				}
			}
		}
		return $roles;
	}
	
	protected function getRowForRole($table_name = '', $id = 0){
		$data = array();
// print_r($this->params);		
// print_r("table_name = $table_name, id = $id");
		if(!empty($id)){
			$strID = $id;
			if(is_array($id))
				$strID = implode(',', $id);
			$this->tool->setDb($this->params['db']);
			$res = $this->tool->query("SELECT * FROM $table_name WHERE id IN ($strID)");
			$data = $res->fetch();
		}
		return $data;
	}
	
	protected function getRowRoleMatrix($row){
		$matrix = array(
			'creater_id'=>'row_owner',
			'owner_id'=>'row_owner',
			'updater_id'=>'row_owner',
			'assistant_owner_id'=>'row_assistant_owner',
			'tester_id'=>'row_tester',
		);
		return $matrix;
	}
	
	public function roleAndStatus($table_name = '', $id = 0, $userId = 0, $fields = array()){
		$row = array();
		if (empty($table_name))
			$table_name = $this->get('table');
		if(!empty($id)){
			$res = $this->tool->query("SELECT * FROM $table_name WHERE id=$id");
			$row = $res->fetch();
		}

		$role = 'guest';
		if (empty($userId))
			$userId = $this->userInfo->id;
		if (!empty($userId)){
			$role = 'normal';
			if ($this->userAdmin->isAdmin($userId))
				$role = 'admin';
			else{
				if(isset($row)){
					if(isset($row['creater_id']) && $row['creater_id'] == $userId)
						$role = 'owner';
					else if(isset($row['assistant_owner_id']) && $row['assistant_owner_id'] == $userId)
						$role = 'owner';
					else if(isset($row['owner_id']) && $row['owner_id'] == $userId)
						$role = 'owner';
					else if(isset($row['tester_ids'])){
						$testers = explode(',', $row['tester_ids']);
						if (in_array($userId, $testers))
							$role = 'tester';
					}
					else if(isset($row['tester_id']) && $row['tester_id'] == $userId)
						$role ='tester';
				}
			}
		}
		$ret = array('role'=>$role);
		foreach($fields as $k=>$f){
			if (isset($row[$f]))
				$ret[$k] = $row[$f];
		}
		return $ret;
	}

	protected function getFieldLabel($column){
		return isset($column['COLUMN_NAME']) ? g_str($column['COLUMN_NAME']) : '';
	}
	
	protected function singleColModel($key, $column, $params = array()){
		// print_r($this->options);
// print_r("key = $key, prop = ");
// if($key == 'pid')
// print_r($column);
		$displayField = $this->getDisplayField();
		$db = $this->get('db');
		$table = $this->get('table');
		if (empty($column['COLUMN_NAME']))
			$column['COLUMN_NAME'] = $key;
		else if(!isset($column['from']))
			$column['from'] = "$db.$table";
		if (empty($column['DATA_TYPE'])){
			$column['DATA_TYPE'] = 'varchar';
			$column['LENGTH'] = 20;
			// $column['editable'] = true;
			$column['IDENTITY'] = false;
		}
		// if(!isset($column['edittype']) && isset($column['formatter']))
			// $column['edittype'] = $column['formatter'];
		if(!isset($column['editoptions']) && isset($column['formatoptions'])){
				$column['editoptions'] = $column['formatoptions'];
		}
		if(!isset($column['addoptions']) && isset($column['editoptions'])){
				$column['addoptions'] = $column['editoptions'];
		}
		elseif(isset($column['addoptions']) && !isset($column['editoptions'])){
				$column['editoptions'] = $column['addoptions'];
		}
		// if(empty($column['type']))
			// $column['type'] = 'text';
		$columnDef = array(
			'hidedlg'=>false, 
			'hidden'=>false, 
			'sortable'=>true, 
			'length'=>isset($column['LENGTH']) ? $column['LENGTH'] : 20,
			'formatter'=>isset($column['formatter']) ? $column['formatter'] : '',               
			'formatoptions'=>array(),
			'formoptions'=>array(), //???
			'label'=>$this->getFieldLabel($column),//ucwords(isset($column['COLUMN_NAME']) ? str_replace('_', ' ', $column['COLUMN_NAME']) : ''),
			'name' =>$key, //$column['COLUMN_NAME'],
			'index'=>$key, //$column['COLUMN_NAME'],
			'defval'=>isset($column['DEFAULT']) ? $column['DEFAULT'] : '',
			'editable'=>isset($column['IDENTITY']) ? !$column['IDENTITY'] : false,
			// 'edittype'=>isset($column['edittype']) ? $column['edittype'] : '',
			'editoptions'=>array('defaultValue'=>isset($column['DEFAULT'])?$column['DEFAULT'] : ''),
			'addoptions'=>array('defaultValue'=>isset($column['DEFAULT'])?$column['DEFAULT'] : ''),
			'editrules'=>array('edithidden'=>true, 'required'=>isset($column['NULLABLE']) ? !$column['NULLABLE'] : false),
			'search'=>true,
			'stype'=>isset($column['stype']) ? $column['stype'] : 'text',
			'searchoptions'=>array('searchhidden'=>true),
			'data_source_db'=>isset($column['data_source_db']) ? $column['data_source_db'] : '',
			'data_source_table'=>isset($column['data_source_table']) ? $column['data_source_table'] : '',
			'search_field'=>isset($column['search_field']) ? $column['search_field'] : '',
			// 'limit'=>false
		);
		$columnDef = $this->tool->array_extends($columnDef, $column);
		
// if($column['COLUMN_NAME'] == 'owner_id'){
// // print_r($columnDef);
// // print_r($column);
// }
		// if(empty($column['TABLE_NAME']))
			// $columnDef['view'] = false;
		switch($column['DATA_TYPE']){
			case 'char':
			case 'varchar':
				if(empty($columnDef['edittype']))
					$columnDef['edittype'] = 'text';
				
				if ($columnDef['edittype'] == 'text' && $column['LENGTH'] > 255){
					$columnDef['edittype'] = 'textarea';
					// $columnDef['editoptions']['rows'] = 3;
				}
				break;
			case 'text':
				if(empty($columnDef['edittype'])){
					$columnDef['edittype'] = 'textarea';
					// $columnDef['editoptions']['rows'] = 3;
					$columnDef['editrules']['required'] = false;
				}
				break;
			case 'int':
			case 'bigint':
			case 'mediumint':
			case 'smallint':
				$columnDef['formatter'] = 'integer';
				$columnDef['sorttype'] = 'int';
				$columnDef['editrules']['int'] = true;
				break;
			case 'tinyint': // BOOL, checkbox, 1:TRUE, 2:FALSE
				$columnDef['edittype'] = $columnDef['stype'] = $columnDef['formatter'] = 'select';
				$columnDef['formatoptions'] = $columnDef['editoptions'] = array('value'=>array(0=>' ', BOOL_TRUE=>'TRUE', BOOL_FALSE=>'FALSE'));
				$columnDef['searchoptions'] = array('value'=>array(0=>' ', BOOL_TRUE=>'TRUE', BOOL_FALSE=>'FALSE'));
				break;
			case 'decimal':
			case 'float':
				$columnDef['formatter'] = 'number';
				$columnDef['editrules']['number'] = true;
				break;
			case 'date':
				$columnDef['width'] = 60;
				$columnDef['editrules']['date'] = true;
				$columnDef['sorttype'] = 'date';
				$columnDef['editoptions']['dataInit'] = $columnDef['addoptions']['dataInit'] = $columnDef['searchoptions']['dataInit'] = 'XT.datePick';
				$columnDef['editoptions']['defval'] = $columnDef['defval'] = date('Y-m-d');
//					$columnDef['searchoptions']['dataInit']['attr']['title'] = 'Select Date';
				break;
				
			case 'timestamp':
				$columnDef['editable'] = false;
			case 'datetime':
				$columnDef['width'] = 100;
				break;
		}
		if(isset($column['COLUMN_NAME'])){
			switch(strtolower($column['COLUMN_NAME'])){
				case 'id':
					$columnDef['width'] = 40;
					$columnDef['editable'] = false;
					$columnDef['key'] = true;
					if ($displayField != 'id')
						$columnDef['hidden'] = true;
					// $limited = $this->getLimit($this->params);
					// if(!empty($limited) && isset($limited['id']))
						// $columnDef['limit'] = $limited['id'];
					break;
					
				case 'isactive':
					$columnDef['edittype'] = $columnDef['stype'] = $columnDef['formatter'] = 'select';
					$columnDef['editoptions']['defval'] = 'Active'; //$columnDef['searchoptions']['defval'] = 'Active';
					$columnDef['editoptions']['value'] = $columnDef['formatoptions']['value'] = array(1=>'Active', 2=>'Inactive');
					$columnDef['searchoptions']['value'] = array(0=>' ', 1=>'Active', 2=>'Inactive');
					$columnDef['width'] = 50;
					$columnDef['editable'] = false;
					break;
					
				case 'email':
					$columnDef['editrules']['email'] = true;
					$columnDef['formatter'] = 'email';
					break;
					
				case 'created':
				case 'modified':
				case 'updated':
					$columnDef['hidden'] = true;
					//如果是_history，则不隐藏
					if(!empty($this->options['is_history'])){
					// if(stripos($table, '_history') !== false){
						$columnDef['hidden'] = false;
					}
					$columnDef['editable'] = false;
					$columnDef['editrules']['required'] = false;
					break;
					
				case 'gender':
					$columnDef['edittype'] = $columnDef['stype'] = $columnDef['formatter'] = 'select';
					$columnDef['editoptions']['defval'] = 'Male';
					$columnDef['editoptions']['value'] = $columnDef['formatoptions']['value'] = array(0=>' ', 1=>'Male', 2=>'Female');
					$columnDef['searchoptions']['value'] = array(0=>' ', 1=>'Male', 2=>'Female');
					break;
					
				case 'ver':
					$columnDef['formatter'] = 'updateViewEditPage';
					$columnDef['editable'] = false;
					break;
					
				case 'file_name':
					$columnDef['formatter'] = 'downloadLink';
					break;
					
				case '__intertag':
					$columnDef['edittype'] = $columnDef['stype'] = 'select';
					$tags = $this->tool->fetch_tags($db, $table);
					$tagOptions = array(0=>'==Select Tag==');
					foreach($tags as $tag)
						$tagOptions[$tag['id']] = $tag['name'];
					$columnDef['editoptions']['value'] = $columnDef['searchoptions']['value'] = $this->tool->array2Str($tagOptions);
					break;
					
				default:
					if(isset($column['formatter']) && ($column['formatter'] == 'multi_row_edit' || $column['formatter'] == 'embed_table')){
						$optionDb = $db;
						$optionTable = $key;
						if(!empty($column['data_source_db']))
							$optionDb = $column['data_source_db'];
						if(!empty($column['data_source_table']))
							$optionTable = $column['data_source_table'];
// if($column['COLUMN_NAME'] == 'hb_contact_method'){
// print_r($column);
// }
						$columhDef['search'] = false;
						$columnDef['data_source_db'] = $optionDb;
						$columnDef['data_source_table'] = $optionTable;
						// subitem table
						$itemParams = isset($column['itemParams']) ? $column['itemParams'] : array('id'=>isset($params['id']) ? $params['id'] : 0);
						if($column['formatter'] == 'multi_row_edit')
							$multiRowEdit = $this->tool->getMultiRowEditTemplate($optionDb, $optionTable, array(), $itemParams, array($this->get('real_table').'_id'));
						else
							$multiRowEdit = $this->tool->embed_table($optionDb, $optionTable, array(), $itemParams, array($this->get('real_table').'_id'));
							
						$columnDef = array_merge($columnDef, $multiRowEdit);
						$columnDef['editable'] = true;
						//
						if(empty($column['from'])){
							$index = $optionDb.'.'.$optionTable;
							if(!empty($this->options['linkTables']['one2m'][$index]))
								$column['from'] = $optionDb.'.'.$optionTable;
						}
						
// print_r($multiRowEdit['temp'])						;
					}
					elseif (preg_match('/^(.+)_(ids?|items)$/i', $column['COLUMN_NAME'], $matches)){
//print_r($matches);					
						$optionDb = $this->get('db');
						$optionTable = $matches[1];
						switch($matches[1]){
							case 'creater':
							case 'creator':
							case 'updater':
								$columnDef['editable'] = false;
							case 'tester':
							case 'assistant_owner':
							case 'testor':
							case 'used_by':
							case 'owner':
							case 'executer':
							case 'controller':
							case 'manager':
							case 'user':
							case 'users':
								// $useradmin = useradminFactory::get();//还需要对可用的记录进行限制，只允许显示本公司的员工
								$userTable = explode('.', $this->userAdmin->getUserTable());
// print_r($userTable);
								$optionDb = $userTable[0];//'useradmin';
								$optionTable = 'users';//$userTable[1];//'users';
								// $userInfo = $useradmin->getUserInfo();
								$columnDef['defval'] = $columnDef['editoptions']['defaultValue'] = $this->userInfo->id; 
								break;
							case 'groups':
							case 'group':
								// $useradmin = useradminFactory::get(); //也应该只能取本公司的group
								$userTable = explode('.', $this->userAdmin->getUserTable());
								
								$optionDb = $userTable[0];//'useradmin';
								$optionTable = 'groups';//$userTable[0];//'useradmin';
// print_r("optionDb = $optionDb, table = $optionTable\n");								
								// $optionDb = 'useradmin';
								break;
						}
						if(!empty($column['data_source_db']))
							$optionDb = $column['data_source_db'];
						if(!empty($column['data_source_table']))
							$optionTable = $column['data_source_table'];
						// dbFactory::get($optionDb, $r_db);
						// $optionDb = $r_db;
						if($this->tool->tableExist($optionTable, $optionDb)){
							$columnDef['data_source_db'] = $optionDb;
							$columnDef['data_source_table'] = $optionTable;
						}
// print_r($columnDef);						
						if(!empty($column['data_source_sql']))$columnDef['data_source_sql'] = $column['data_source_sql'];
						if(!empty($column['data_source_condition']))$columnDef['data_source_condition'] = $column['data_source_condition'];
						$columnDef['data_source_blank_item'] = isset($column['data_source_blank_item']) ? $column['data_source_blank_item'] : false;
						$columnDef['data_source_all_fields'] = isset($column['data_source_all_fields']) ? $column['data_source_all_fields'] : true;
						$columnDef['label'] = g_str($matches[1]);
						if(empty($column['formatter'])){
							$columnDef['edittype'] = $columnDef['stype'] = 'select';
							$columnDef['formatter'] = 'select';//'select_showlink';
							$columnDef['formatoptions'] = array(
								'baseLinkUrl'=>'/jqgrid/jqgrid/newpage/1/oper/information/db/'.$db.'/table/'.$matches[1],
								'target'=>'blank',
								'db'=>$optionDb,
								'table'=>$optionTable);
						}
						if ($matches[2] == 'ids'){
							if(empty($column['formatter'])){
								$columnDef['formatter'] = 'ids';
								// $columnDef['edittype'] = 'select';
								$columnDef['editoptions']['multiple'] = $columnDef['addoptions']['multiple'] = $columnDef['formatoptions']['multiple'] = 'true';
								$columnDef['editoptions']['size'] = $columnDef['addoptions']['size'] = 5;
							}
						}
						//看看是否存在m2m关系，依次设置from属性
						if(empty($column['from'])){
// print_r($matches);						
// print_r($this->options['linkTables']);
// print_r($optionDb);
// print_r($optionTable);
							if(!empty($this->options['linkTables']['m2m']["$optionDb.{$matches[1]}"])){
								$link = $this->options['linkTables']['m2m']["$optionDb.{$matches[1]}"];
// print_r($link);								
								$columnDef['from'] = $link['db'].'.'.$link['table'];
								$columnDef['formatter'] = 'ids';
								$columnDef['search'] = true;
								if (!empty($columnDef['edittype']) && $columnDef['edittype'] == 'select'){
									$columnDef['editoptions']['multiple'] = $columnDef['addoptions']['multiple'] = $columnDef['formatoptions']['multiple'] = 'true';
									$columnDef['editoptions']['size'] = $columnDef['addoptions']['size'] = 5;
								}
// print_r($columnDef);								
							}
							elseif(!empty($this->options['linkTables']['node_ver_m2m']["$optionDb.{$matches[1]}"])){
								$link = $this->options['linkTables']['node_ver_m2m']["$optionDb.{$matches[1]}"];
								$columnDef['from'] = $link['db'].'.'.$link['table'];
								$columnDef['formatter'] = 'ids';
								$columnDef['search'] = true;
							}
						}
						if(!empty($column['type']) && $column['type'] == 'cart'){
							$columnDef['cart_db'] = $optionDb;
							$columnDef['cart_table'] = $matches[1];
						}
					}
					break;
			}
			
			if ($column['COLUMN_NAME'] == $displayField){
				$columnDef['displayField'] = true;
				$columnDef['formatter'] = isset($this->options['linktype']) ? $this->options['linktype'] : 'infoLink_dialog';
				// display field 应该是唯一的，在输入后应进行检查
				if (!isset($columnDef['unique']) && ($displayField == 'code' || $displayField == 'name' || $displayField == 'username'))
					$columnDef['unique'] = true;
			}
		}
		
// if($key == 'testcase_type_ids')
// print_r($columnDef);
		$from = '';
		if(!empty($column['from'])){
			$from = $column['from'];
		}
		elseif(!empty($columnDef['from']))
			$from = $columnDef['from'];
		if(!empty($from)){
			$tmp = explode('.', $from);
			if(count($tmp) == 1){
				$tmp[1] = $tmp[0];
				$tmp[0] = $db;
			}
			// dbFactory::get($tmp[0], $r_db);
			// $tmp[0] = $r_db;
			$column['from'] = implode('.', $tmp);
			// $columnDef['formatoptions']['db'] = $db;
			// $columnDef['formatoptions']['table'] = $tmp[1];
		}
		$columnDef = $this->tool->array_extends($columnDef, $column);
		
		if (!$columnDef['editable'])
			$columnDef['editrules']['required'] = false;
		if ($columnDef['editrules']['required']){
			$columnDef['classes'] = 'required';
			// $columnDef['formoptions']['elmsuffix'] = '(*)';
		}	
// if($key == 'prj_ids'){
	// if(isset($column['editable']) && $columnDef['editable'] != $column['editable']){
		// print_r("columnDef = {$columnDef['editable']}, column = {$column['editable']}");
	// }
	// print_r($column);
	// print_r("editable = {$columnDef['editable']}\n");
// }
// if($columnDef['name'] == 'testcase_type_id'){
// print_r($this->params['table']);	
	// print_r($columnDef);
	// print_r($column);
// }
		return $columnDef;
	}
	
	public function getCellInfo($cell_id){
		// print_r($this->options['view'][$cell_id]);
		if($this->params['display_status'] == DISPLAY_STATUS_EDIT)
			return $this->options['edit'][$cell_id];
		if($this->params['display_status'] == DISPLAY_STATUS_NEW)
			return $this->options['add'][$cell_id];
		if($this->params['display_status'] == DISPLAY_STATUS_VIEW)
			return $this->options['view'][$cell_id];
	}
}

?>