<?php
require_once('action_jqgrid.php');

class action_save extends action_jqgrid{
	protected $orig_id = 0;
	protected function handlePost(){
// print_r("handle Post");
// print_r($this->params);
		$errorCode = array('code'=>ERROR_OK, 'msg');
    	$affectedID = 0;
		$realTable = $this->table_desc->get('real_table');
// print_r("realTable = $realTable\n");		
    	try{
    	    // save 
			$errorCode = $this->beforeSave($this->db_name, $this->table_name, $this->params);
// print_r("bf save:");
// print_r($errorCode);

			if ($errorCode['code'] == ERROR_OK){
				$errorCode['msg'] = $affectedID = $this->save($this->db_name, $realTable, $this->params);
				$ret = $this->afterSave($affectedID);
				if(!empty($ret)){
					//判断是否treeview,如果是treeview,则需要将返回的id作为最后的id
					$linkTables = $this->table_desc->getLinkTables();
					if(isset($linkTables['treeview'])){
						$errorCode['msg'] = $ret['msg'];
					}
					else{
						$errorCode['msg'] .= ':'.$ret['msg'];
					}
				}
			}
			else{
				throw new Exception(json_encode($errorCode));
				return $errorCode;
			}
    	}catch(Exception $e){
    		$errorCode['code'] = ERROR_UNKNOWN;
    		$errorCode['msg'] = $e->getMessage();
    		throw new Exception(json_encode($errorCode));
    		return $errorCode;
    	}
		return $errorCode;
    }
	
	protected function save($db, $table, $pair){
// print_r($pair);
		$pair = $this->prepare($db, $table, $pair);
// print_r($pair);
		return $this->_saveOne($db, $table, $pair);
	}
	
	protected function beforeSave($db_name, $table_name, &$params){
		if(isset($this->params['cloneit']) && $this->params['cloneit'] != 'false'){
			$this->orig_id = $this->params['id'];
			$this->params['id'] = null;
		}
		//在此检查字段唯一性
		$displayField = $this->table_desc->getDisplayField();
// print_r($displayField);
		$history = substr($table_name, -8);
		$history_table = $history == '_history';
// print_r("history = $history, history_table = $history_table\n");		
		if($displayField != 'id' && $displayField != 'ver' && !$history_table && isset($this->params[$displayField])){
// print_r($this->params);			
// print_r($params);
			$checkUniq_action = actionFactory::get(null, 'checkUnique', array('db'=>$db_name, 'table'=>$table_name, 'field'=>$displayField, 'value'=>$this->params[$displayField], 'op'=>'checkUnique', 'id'=>isset($this->params['id']) ? $this->params['id'] : 0));
			$ret = $checkUniq_action->handlePost();
			if($ret > 1){
				$errorCode['code'] = ERROR_DATA_DUPLICATE;
				$errorCode['msg'] = "The $displayField in $table_name is duplidate";
				return $errorCode;
			}
		}
		$errorCode['code'] = ERROR_OK;
		$errorCode['msg'] = "Ok";
		return $errorCode;
	}
	
	protected function saveOne2One($affectedID, $linkInfo){
		$db = $linkInfo['db'];
		$table = $linkInfo['table'];
		$from = $db.'.'.$table;
		$data = array();
		$options = $this->table_desc->getOptions();
		foreach($options['list'] as $field=>$model){
// print_r($field);			
			if(isset($model['from']) && $model['from'] == $from && isset($this->params[$field])){
				$data[$field] = $this->params[$field];
			}
		}
		
		// $list = $options[]
// print_r("In saveOne2One\n");		
// print_r($linkInfo);
// print_r($this->params);
// print_r($data);
		// return;
		// if(!empty($this->params[$table])){
			// // $data = $this->params[$table];
			// $data = $this->params;
			unset($data['id']);
			$data[$linkInfo['self_link_field']] = $affectedID;
			$res = $this->tool->query("SELECT * FROM $table WHERE {$linkInfo['self_link_field']}=$affectedID");
			if($row = $res->fetch()){
				$data['id'] = $row['id'];
			}
			$data = $this->prepareForOne2One($data);
			$params = array_merge(compact('db', 'table'), $data);
// print_r("in saveOne2One, params = ");
// print_r($params);
			$save_action = actionFactory::get(null, 'save', $params);
			return $save_action->handlePost();
		// }
	}
	
	protected function prepareForOne2One($data){
		return $data;
	}
	
	protected function deleteLinkTable($rel, $affectedID, $linkInfo){
		$params = $linkInfo;
		switch($rel){
			case 'one2m':
				$params['index_field'] = $linkInfo['self_link_field'];
				$params['id'] = $affectedID;
				$params['table'] = $linkInfo['link_table'];
// print_r($params);
				break;
			case 'm2m':
			case 'node_ver_m2m':
				$params['db'] = $linkInfo['link_db'];
				$params['table'] = $linkInfo['link_table'];
				$params['index_field'] = $linkInfo['self_link_field'];
				$params['id'] = $affectedID;
				break;
		}
		$del_action = actionFactory::get(null, 'del', $params);
		$del_action->handlePost();
	}
	
	protected function saveOne2M($affectedID, $linkInfo){
// print_r("in saveOne2M, linkInfo = ");
// print_r($linkInfo);
// print_r($this->params);	
		$db = $linkInfo['db'];
		$real_table = $table = $linkInfo['link_table'];
		if(isset($linkInfo['real_table']))
			$real_table = $linkInfo['real_table'];
		$params = array('db'=>$db, 'table'=>$table);
		$this->deleteLinkTable('one2m', $affectedID, $linkInfo);
// print_r("after deleteLinkTable");		
		if(!empty($this->params[$table])){
			$data = $this->params[$table];
// print_r($data);
			if(is_string($data)){
				$data = json_decode($data, true);
			}
// print_r($params);
// print_r($data);
			if(!empty($data['data'])){
	// print_r(">>>>>>");
	// print_r($data);			
				// $this->tool->delete($real_table, $linkInfo['self_link_field']."=$affectedID");
	// print_r($params);			
				$action_save = actionFactory::get(null, 'save', $params);
				//要先处理item，将同类项合并
				$items = array();
				if(!empty($linkInfo['link_field'])){
					$linkField = $linkInfo['link_field'];
					foreach($data['data'] as $item){
// print_r("items = ");
// print_r($item);
						unset($item['id']);
						if(empty($items[$linkField]))
							$items[$item[$linkField]] = $item;
						else
							$items[$item[$linkField]] = $this->tool->array_extends($items[$item[$linkField]], $item);
					}
				}
				else
					$items = $data['data'];
// print_r("Last items = ");				
// print_r($items);
				foreach($items/*$data['data']*/ as $item){
					$item[$linkInfo['self_link_field']] = $affectedID;
					$item = array_merge($params, $item);
		// print_r($item);
					$action_save->setParams($item);
					$action_save->handlePost();
				}
	// print_r("<<<<<<<<<");
			}
		}
	}
	
	protected function 	saveNodeVerM2M($affectedID, $linkInfo){ //一定是Version的Save
	// print_r("saveNOdeVerM2M\n");
// print_r($linkInfo);	
		if(empty($linkInfo['node_field']) || empty($linkInfo['link_node_field']))
			return;
		$link_db = $linkInfo['link_db'];
		$link_table = $linkInfo['link_table'];
		$node_field = $linkInfo['node_field'];
		$link_node_field = $linkInfo['link_node_field'];
		
		$data = isset($this->params[$linkInfo['table'].'_ids']) ? $this->params[$linkInfo['table'].'_ids'] : null;
		$node_id = $this->params[$node_field];
		$this->deleteLinkTable('node_ver_m2m', $affectedID, $linkInfo);
		$action_save = actionFactory::get(null, 'save', array('db'=>$link_db, 'table'=>$link_table));
		if(!empty($data)){
			if(is_string($data))
				$data = explode(',', $data);
			// $this->tool->delete($linkInfo['link_table'], $linkInfo['self_link_field'].'='.$affectedID, $this->params['db']);
			foreach($data as $e){
				$params = array('db'=>$link_db, 'table'=>$link_table, $link_node_field=>$node_id, $linkInfo['self_link_field']=>$affectedID, $linkInfo['link_field']=>$e);
// print_r("saveNodeVerM2M:");
// print_r($params);
				$action_save->setParams($params);
				$action_save->handlePost();
			}
		}
	}

	protected function saveM2M($affectedID, $linkInfo){
// print_r($linkInfo);	
// print_r($this->params);
		$this->deleteLinkTable('m2m', $affectedID, $linkInfo);
		$link_db = $linkInfo['link_db'];
		$link_table = $linkInfo['link_table'];
		$data = isset($this->params[$linkInfo['table'].'_ids']) ? $this->params[$linkInfo['table'].'_ids'] : 
			(isset($this->params[$linkInfo['table'].'_id']) ? $this->params[$linkInfo['table'].'_id'] : array());
// print_r($data);		
// print_r(array('db'=>$link_db, 'table'=>$link_table));
		$action_save = actionFactory::get(null, 'save', array('db'=>$link_db, 'table'=>$link_table));
		if(is_string($data))
			$data = explode(',', $data);
		// $this->tool->delete($linkInfo['link_table'], $linkInfo['self_link_field'].'='.$affectedID, $this->params['db']);
		foreach($data as $e){
			$params = array('db'=>$link_db, 'table'=>$link_table, $linkInfo['self_link_field']=>$affectedID, $linkInfo['link_field']=>$e);
// print_r("saveM2M:");
// print_r($params);
			$action_save->setParams($params);
			$action_save->handlePost();
		}
	}
	
	//Version表有Ver_field和edit_status_id，根据edit_status_id来决定是否需要生成新的Version
	protected function saveVer($affectedID, $linkInfo){
// print_r("saveVer\n");		
// print_r($linkInfo);	
		$db = $linkInfo['real_db'];
		$table = $linkInfo['table'];
		$ver_action_save = actionFactory::get(null, 'save', array('db'=>$linkInfo['db'], 'table'=>$table));
		$data = $this->params;
		unset($data['id']);
		$data['db'] = $linkInfo['db'];
		$data['table'] = $table;
		$data[$linkInfo['self_link_field']] = $affectedID;
		//获取当前Version的状态
		$ver_field = $linkInfo['ver_field'];
		$data[$ver_field] = 1;
// print_r($data);
		if(!empty($data['ver_id'])){
// print_r("SELECT * FROM $db.$table WHERE id={$data['ver_id']}");	
			$res = $this->tool->query("SELECT * FROM $db.$table WHERE id={$data['ver_id']}");
			if($ver = $res->fetch()){
// print_r($row);			
				$edit_status_id = $ver['edit_status_id'];
				if($edit_status_id == EDIT_STATUS_PUBLISHED || $edit_status_id == EDIT_STATUS_GOLDEN){
					$new_ver = true;
					//默认Ver字段是ver, 获取最大的Ver
					$res = $this->tool->query("SELECT max($ver_field) as max_ver FROM $db.$table WHERE {$linkInfo['self_link_field']}=$affectedID");
					$row = $res->fetch();
					$max_ver = $row['max_ver'];
					$data[$ver_field] = $max_ver + 1;
					$data['update_from'] = $ver['ver'];
				}
				else{
					$data['id'] = $ver['id'];
					$data[$ver_field] = $ver[$ver_field];
				}
			}
		}
		$data['edit_status_id'] = EDIT_STATUS_EDITING;
// print_r($data);		
// print_r("saveVer:");	
// print_r($ver_action_save);	
// print_r($data);
		$ver_action_save->setParams($data);
		$ret = $ver_action_save->handlePost();
// print_r($ret);		
		return $ret;
	}
	
	protected function saveHistory($affectedID, $linkInfo){
		$db = $linkInfo['db'];
		$table = $linkInfo['table'];
		$his_action_save = actionFactory::get(null, 'save', array('db'=>$db, 'table'=>$table));
		$data = $this->params;
		unset($data['id']);
		$data['db'] = $db;
		$data['table'] = $table;
		$data[$linkInfo['self_link_field']] = $affectedID;

		$his_action_save->setParams($data);
		$ret = $his_action_save->handlePost();
		return $ret;
	}
	
	protected function saveTree($affectedID, $linkInfo){
		$db = $linkInfo['db'];
		$table = $linkInfo['tree_table'];
		$his_action_save = actionFactory::get(null, 'save', array('db'=>$db, 'table'=>$table));
		$data = $this->params;
		unset($data['id']);
		$data['db'] = $db;
		$data['table'] = $table;
		$data['node_id'] = $affectedID;
		if (!empty($data['pid']))
			$data['ps'] = $data['pid'].'-'.$affectedID;
		else
			$data['ps'] = $affectedID;
			
		$his_action_save->setParams($data);
		$ret = $his_action_save->handlePost();
		return $ret;
	}
	
	protected function afterSave($affectedID){
		$ret = array();
		$linkTables = $this->table_desc->getLinkTables();
// print_r("table = ".$this->get('table'));
// print_r($linkTables);		
		if(!empty($linkTables)){
// print_r($linkTables);		
			foreach($linkTables as $rel=>$relData){
// print_r($rel);
				foreach($relData as $linkInfo){
// print_r(">>>>>>>>>>>>>rel = $rel, ");					
// print_r($linkInfo);					
					switch($rel){
						case 'one2one':
							$ret = $this->saveOne2One($affectedID, $linkInfo);
							break;
						case 'one2m':
							$this->saveOne2M($affectedID, $linkInfo);
							break;
						case 'm2m':
							$ret = $this->saveM2M($affectedID, $linkInfo);
							break;
						case 'node_ver_m2m':
							$ret = $this->saveNodeVerM2M($affectedID, $linkInfo);
							break;
						case 'ver':
							$ret = $this->saveVer($affectedID, $linkInfo);
							break;
						case 'history':
							$ret = $this->saveHistory($affectedID, $linkInfo);
							break;
						case 'treeview':
							$ret = $this->saveTree($affectedID, $linkInfo);
							break;
					}
				}
			}
		}
		return $ret;
	}
	
	protected function saveMultiRowEditValue($row, $linkInfo){
// print_r($row);
// print_r($linkInfo);
		$this->tool->insert($linkInfo['link_table'], $row);
	}
	
	protected function saveEmbedTable($row, $linkInfo){
// print_r($row);
// print_r($linkInfo);
		$this->tool->insert($linkInfo['link_table'], $row);
	}
	
    protected function _saveOne($db, $table, $pair){
// print_r("_saveOne:$db, $table");
// print_r($pair);
		if (empty($pair['id'])){
			$this->fillDefaultValues('new', $pair, $db, $table);
			return $this->newRecord($db, $table, $pair);
    	}
		$this->fillDefaultValues('update', $pair, $db, $table);
		return $this->updateRecord($db, $table, $pair);
    }
	
	protected function prepare($db, $table, $pair){
		if(isset($pair['cloneit']) && $pair['cloneit'] == 'true'){
			unset($pair['id']);
			unset($pair['update_comment']);
			unset($pair['issue_comment']);
			unset($pair['review_comment']);
		}
		// dbFactory::get($db, $real_db);
		$pair = $this->tool->extractData($pair, $table, $db);
		return $pair;
	}
	
	protected function newRecord($db, $table, $pair){
		// $this->fillDefaultValues('new', $pair, $db, $table);
		// dbFactory::get($db, $real_db);
// print_r("db = $db, table = $table, $real_db\n");		
		$real_table = $this->table_desc->get('real_table');
		$affectedID = $this->tool->insert($real_table, $pair, $db);
		
// // print_r($pair);		
		// $this->db->insert($db.'.'.$table, $pair);
		// $affectedID = $this->db->lastInsertId();
		return $affectedID;
	}
	
	protected function updateRecord($db, $table, $pair, $id = 'id'){
		// 填入一些默认值：updater_id, updated
		// $this->fillDefaultValues('update', $pair, $db, $table);
// print_r($pair);		
		// dbFactory::get($db, $real_db);
// print_r("update $db, $table, $real_db, $id\n");
		$affectedID = $pair[$id];
		$real_table = $this->table_desc->get('real_table');
		$this->tool->update($real_table, $pair, '', $db);
		// $this->db->update($db.'.'.$table, $pair, $id.'='.$pair[$id]);
		return $affectedID;
	}
	
	protected function fillDefaultValues($action, &$pair, $db, $table){
		// dbFactory::get($db, $real_db);
		foreach($pair as &$v){
			if (is_array($v))
				$v = implode(',', $v);
		}
		$tableFields = $this->tool->getTableFields($table, $db);
		switch($action){
			case 'new':
				$defaultUserFields = array('owner_id', 'creater_id', 'creator_id', 'updater_id');
				$defaultTimeFields = array('created', 'updated');
				break;
			case 'update':
				$defaultUserFields = array('updater_id');
				$defaultTimeFields = array('updated');
		}
		foreach($defaultUserFields as $df){
			if (in_array($df, $tableFields)){
				if(empty($pair[$df]) || $df == 'updater_id')
					$pair[$df] = $this->userInfo->id;
			}
		}
		foreach($defaultTimeFields as $df){
			if (in_array($df, $tableFields)){
				if(empty($pair[$df]) || $df == 'updated')
					$pair[$df] = date('Y-m-d H:i:s');
			}
		}
	}
}
?>