<?php
require_once('action_jqgrid.php');

class action_list extends action_jqgrid{
	protected $models = array();
	protected $colModelMap = array();
	protected $moreInfoFields = array();
	protected $special = array();
	
	public function setParams($params){
		$params['fill'] = false;
		parent::setParams($params);
	}
	
	protected function handleGet(){
		return $this->getOptions();
	}
	
	protected function handlePost(){
		$rows = array();
		$sql = '';
		$sqls = array();
        $ret = array();
// print_r("table = {$this->params['table']}\n");		
		$options = $this->getOptions();
        // $this->config();
// print_r($options);
		$this->models = $options['list'];//['gridOptions']['colModel'];
		$this->colModelMap = $options['gridOptions']['colModelMap'];
		$this->params = $this->filterParams();
        $rownum = $this->params['limit']['rows'];
		if ($rownum == 0)
			$rownum = 'ALL';
		if($rownum != 1){ //rownum = 1 往往是information传过来的，不要保存到cookie
			$cookie = array('type'=>'rowNum', 'name'=>$this->db_name.'_'.$this->table_name, 'content'=>json_encode(array('rowNum'=>$rownum)));
			$this->saveCookie($cookie);
		}
		$sqls = $this->calcSqlComponents($this->params, true);
		// $mainFields = $sqls['main']['fields'];
		// $sqls['main']['fields'] = "`{$this->table_desc->get('table')}`.`id`"; //在table_desc里可能更改table
		$limitedSql = $sqls['limit'];
		if($rownum != 'ALL' && $rownum != 1) //需要分页
			unset($sqls['limit']);
        $sql = $this->tool->getSql($sqls);
// print_r("sql = $sql\n");		
		// $sqls['main']['fields'] = $mainFields;
		$sqlKeys = $this->tool->getSqlKeys();
		$res = $this->tool->query($sql);
		
		$ret['records'] = $res->rowCount();
		
        $ret['page'] = intval($this->params['page']);
// print_r($this->params);
        if ($this->params['limit']['rows'] > 0)
            $ret['pages'] = ceil($ret['records'] / $this->params['limit']['rows']);
		else
			$ret['pages'] = 1;
		
		if($rownum != 'ALL ' && $rownum != 1){
			$sqls['limit'] = $limitedSql;
			$sql = $this->tool->getSql($sqls);
			$res->closeCursor();
			$res = $this->tool->query($sql);
		}
			
        $rows = array();
        while($row = $res->fetch()){
			$row = $this->getMoreInfoForRow($row);
			if (!empty($sqlKeys))
				$row = $this->hilightKeys($row, $sqlKeys);
			$rows[] = $row;
		}
// $t2 = microtime(true)		;
		$res->closeCursor();
        $ret['rows'] = $rows;
        $ret['sql'] = $sql;
		$ret['sqls'] = json_encode($sqls);
		$ret['keys'] = $sqlKeys;
		return $ret;
	}
	
	public function getList(){
// print_r("\n<br>getList :");
		$ret = $this->handlePost();
// print_r("...after getList<br>\n");		
		return $ret['rows'];
	}
	
	protected function hilightKeys($row, $keys){
		foreach($keys as $k=>$v){
// print_r("k = $k, v = $v");		
// print_r($row);
// print_r($this->colModelMap);
			$kk = explode('.', $k);
			$k = $kk[count($kk) - 1];
			if (!empty($row[$k])){
				if(!empty($this->colModelMap[$k]) && !empty($this->models[$this->colModelMap[$k]]) && $this->models[$this->colModelMap[$k]]['formatter'] == 'ids'){
// 主要是不好处理，如1,10,2，如果关键字是1,则10会被误处理
					// $row[$k] = $this->tool->hilitWords($row[$k], array($v), true);
				}
				else
					$row[$k] = $this->tool->hilitWords($row[$k], array($v));
			}
		}
		return $row;
	}
	
	protected function access_limit(){ //根据用户角色等信息确定的可访问范围
		$ret = array();
		$ret = $this->table_desc->getLimit($this->params);
// if($this->params['table'] == 'os')		
	// print_r($ret);
// print_r($ret);		
		return $ret;
	}
	
	protected function filterParams(){
		$params = $this->params;
        $searchConditions = $this->access_limit();//array();
		// if(isset($params['url_add'])){
			// foreach($params['url_add'] as $k=>$v){
				// $searchConditions[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
			// }
		// }
        $limit = array();
        $page = isset($params['page']) ? $params['page'] : 1;
        $sidx = isset($params['sidx']) ? $params['sidx'] : '';
        $ord = isset($params['sord']) ? $params['sord'] : '';
        $order = !empty($sidx) ? $sidx.' '.$ord : '';
        if (empty($params['rows']))
            $limit['rows'] = 0;
        else if ($params['rows'] != 'ALL' && $params['rows'] != -1)
            $limit['rows'] = $params['rows'];
        else
            $limit['rows'] = 0;
        if ($page == 'NaN')
            $page = 1;
        $limit['start'] = ($page - 1) * $limit['rows'];
        
        if (!empty($params['filters'])){
//print_r($params['filters']);			
            $json_filters = json_decode($params['filters'], true);
//print_r($json_filters);
            if(is_array($json_filters)){
    			$gopr = strtolower($json_filters['groupOp']);
    			$rules = $json_filters['rules'];
//print_r($rules);				
//                $searchConditions[$gopr] = $this->tool->generateFilterConditions($rules);
                $searchConditions = array_merge($searchConditions, $this->tool->generateFilterConditions($rules));
            }
        }

		//把所有的limit写入SearchConditions
		$colModel = $this->options['list'];//['gridOptions']['colModel'];
		$list_fields = array_keys($this->options['list']);
		$normal_query_fields = array();
		$advanced_query_fields = array();
		if(!empty($this->options['query']['normal']))
			$normal_query_fields = array_keys($this->options['query']['normal']);
		if(!empty($this->options['query']['advanced']))
			$advanced_query_fields = array_keys($this->options['query']['advanced']);
		$lists = array_merge($list_fields, $normal_query_fields, $advanced_query_fields);
		$lists = array_unique($lists);
// _P($lists);		
// _P($colModel);
		foreach($lists as $field){
			if(!isset($this->colModelMap[$field]))
				continue;
			$index = $this->colModelMap[$field];
// print_r("field = $field, index = $index");			
			// $fieldColModel = $colModel[$index];
			// if(!empty($fieldColModel['notfill'])){
				// // $ret = $this->tool->fillOptions($fieldColModel);
				// // $colModel[$index]['limit'] = array_keys($ret['options']);
			// }
// if($field == 'testcase_module_id'){
// print_r("field = $field, limit = ");
// print_r($colModel[$index]);
// }
			if(isset($colModel[$index]['limit']) && $colModel[$index]['limit'] !== false && !empty($colModel[$index]['from'])){
// print_r("index = $index,");
// print_r($colModel[$index]);
				if(empty($colModel[$index]['limit'])){
					// $searchConditions[] = array('field'=>"1", 'op'=>'=', 'value'=>0); // 1=0, false
				}
				else{
					$from = $colModel[$index]['from'];
					$linkInfo = array();
					
					if(isset($this->options['linkTables']['m2m'][$from])){
						$linkInfo = $this->options['linkTables']['m2m'][$from];
					}
					elseif(isset($this->options['linkTables']['node_ver_m2m'][$from])){
						$linkInfo = $this->options['linkTables']['node_ver_m2m'][$from];
					}
					if(!empty($linkInfo)){
						$k = $linkInfo['link_db'].'.'.$linkInfo['link_table'].'.'.$field;
					}
					else	
						$k = $colModel[$index]['from'].'.'.$field;
				
					$searchConditions[] = array('field'=>$k, 'op'=>'IN', 'value'=>$colModel[$index]['limit']);
				}
			}
		}
		
//debug_log($param, 0);    
//print_r($params);
//print_r($this->options['list']);
        if (1 || !empty($params['_search']) && $params['_search'] != FALSE && $params['_search'] != 'false'){
            foreach($params as $k=>$v){
                switch($k){
                    case 'module':
                    case 'controller':
                    case 'action':
                    case 'page':
                    case 'sidx':
                    case 'sord':
                    case '_search':
                    case 'nd':
                    case 'rows':
                    case 'PHPSESSID':
                    case 'filters':
                    case 'nextaction':
                    case 'tabid':
					case 'db':
					case 'table':
					case 'real_table':
					case 'parent':
					case 'container':
					case 'subgrid':
					case 'from':
					case 'newpage':
					case 'tab_div':
					case 'caption':
                        continue;
                        break;
                    default:
// if($print)
// print_r("$k = $v\n");
                        if (empty($v)){ // remove the empty conditions
                            break;
                        }
						$colModel = array();
// if($print)						
// print_r($this->colModelMap);						
// print_r($k);						
						if(isset($this->options['query']['normal'][$k]))
							$colModel = $this->options['query']['normal'][$k];
						elseif(isset($this->options['query']['advanced'][$k]))
							$colModel = $this->options['query']['advanced'][$k];
						elseif(isset($this->colModelMap[$k])){
// print_r($this->options['gridOptions']);
							$colModel = $this->options['list'][$k];//['gridOptions']['colModel'][$this->colModelMap[$k]];
						}
                        try{
							if(!empty($colModel)){
// print_r("k = $k\n");
// print_r($colModel);
							// if (isset($this->options['list'][$k]['DATA_TYPE'])){
								$fieldType = $colModel['DATA_TYPE']; //$this->options['list'][$k]['DATA_TYPE'];
								switch(strtolower($fieldType)){
									case 'int':
									case 'tinyint':
									case 'mediaint':
									case 'float':
									case 'double':
									case 'decimal':
									case 'largeint':
										$op = '=';
										//如果$v有--，则考虑是一个范围，如：100--200是>=100 AND <=200；100--是>=100；--200是<=200是
										
										break;
									case 'char':
									case 'varchar':
									case 'text':
										$op = 'LIKE';
										break;
									case 'time':
									case 'date':
									case 'datetime':
										$op = '>=';
										//如果$v有--，则考虑是一个范围，如：100--200是>=100 AND <=200；100--是>=100；--200是<=200是
										break;
									default:
										$op = '=';
										break;                            
								}
								if(!empty($colModel['from'])){
									$from = $colModel['from'];
									$linkInfo = array();
									
									if(isset($this->options['linkTables']['m2m'][$from])){
										$linkInfo = $this->options['linkTables']['m2m'][$from];
										$k = $linkInfo['real_link_db'].'.'.$linkInfo['link_table'].'.'.$linkInfo['link_field'];
										$op = 'IN';
									}
									elseif(isset($this->options['linkTables']['node_ver_m2m'][$from])){
										$linkInfo = $this->options['linkTables']['node_ver_m2m'][$from];
										$k = $linkInfo['real_link_db'].'.'.$linkInfo['link_table'].'.'.$k;
										$op = 'IN';
									}
									elseif(isset($this->options['linkTables']['ver'][$from])){
										$linkInfo = $this->options['linkTables']['ver'][$from];
										$k = $linkInfo['real_db'].'.'.$linkInfo['table'].'.'.$k;
									}
									elseif(isset($this->options['linkTables']['one2one'][$from])){
										$linkInfo = $this->options['linkTables']['one2one'][$from];
										$k = $linkInfo['real_db'].'.'.$linkInfo['table'].'.'.$k;
									}
									elseif(isset($this->options['linkTables']['one2m'][$from])){
										$linkInfo = $this->options['linkTables']['one2m'][$from];
										$k = $linkInfo['real_link_db'].'.'.$linkInfo['table'].'.'.$k;
									}
									else{
										list($tmp_db, $tmp_table) = explode('.', $colModel['from']);
										dbFactory::get($tmp_db, $real_db);
										$k = $real_db.'.'.$tmp_table.'.'.$k;//$colModel['from'].'.'.$k;
										
									}
// print_r(" has from : $k\n");	
// print_r($this->options['linkTables']);
									$searchConditions[] = array('field'=>$k, 'op'=>$op, 'value'=>$v);
// print_r($searchConditions);									
								}
								else{
									$this->special[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
								}
							}
							else{//if ($k == '__interTag'){
								unset($params[$k]);
								switch($k){
									case 'key':
										$v = trim($v);
										$this->special[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
										// $searchConditions[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
										break;
									case '__interTag':
										$searchConditions[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
										break;
									default:
										$this->special[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
										break;
								}

								// $searchConditions[] = array('field'=>$k, 'op'=>'=', 'value'=>$v);
							}
                        }catch(Exception $e){
                            print_r($e);
                        }
                        break;
                }
            }
        }
// if($print)		
	// print_r($searchConditions);
// print_r("searchconsitions:");
// print_r($searchConditions);  
// print_r("<<<<searchConditions<br>\n");   
// print_r($this->special);   
//debug_log($searchConditions, 0);            
        return array_merge($params, compact('searchConditions', 'page', 'limit', 'order'));
	}
	
	protected function getSpecialFilters(){
		// $special = array();
		// if(!empty($this->options['linkTables'])){
			// foreach($this->options['linkTables'] as $rel=>$relData){
				// foreach($relData as $linkInfo){
					// $db = $linkInfo['db'];
					// $table = $linkInfo['table'];
					// $self_link_field = $linkInfo['self_link_field'];
					// switch($rel){
						// case 'one2one':
							// break;
						// case 'one2m':
							// break;
						// case 'm2m':
							// $special[] = $table.'_id';
							// $special[] = $table.'_ids';
							// break;
						// case 'ver':
							// break;
						// case 'history':
							// break;
					// }
				// }
			// }
		// }
		return $this->special;
	}
	
	protected function specialSql($special, &$ret){
// print_r($special);
// // print_r($this->linkTables);
		// if(!empty($special) && !empty($this->linkTables)){
			// foreach($this->linkTables as $linkTable=>$linkInfo){
				// foreach($special as $c){
					// if(in_array($c['field'], array($linkTable.'_id', $linkTable.'_ids'))){
						// $v = $c['value'];
						// if(is_array($v))
							// $v = implode(',', $v);
						// $ret['main']['from'] .= " LEFT JOIN {$linkInfo['link_table']} ON {$this->get('table')}.id={$linkInfo['link_table']}.{$linkInfo['self_link_field']}";
						// $ret['group'] = $this->get('table').'.id';
						// $ret['where'] .= " AND {$linkInfo['link_table']}.{$linkInfo['link_field']} IN ($v)";
					// }
				// }
			// }
		// }
// // print_r($ret)		;
	}
	
	public function calcSqlComponents($params, $limited = true){
// print_r($params);
		//需要获取正行的Db_name
		$db = $this->tool->getDb($this->get('db'), $real_db);
		$this->getOptions();
		$special = $this->getSpecialFilters();
// print_r($this->options['list']);
		$orig_dbTable = $this->get('db').'.'.$this->get('table');
		$dbTable = $real_db.'.'.$this->get('table');
		$params['table'] = $this->get('table');
		$params['from'] = $dbTable;
// print_r($this->options['linkTables']);
		//处理list fields, 哪些可以放在主sql里，哪些只能在getmoreinfo里处理
		$rels = array();
// print_r($this->options['linkTables']);
		foreach($this->options['list'] as $field=>$prop){
// print_r("field = $field, ");
// print_r(" from = {$prop['from']}\n");
			if(empty($prop['from'])){
				$this->moreInfoFields['unknown']['unknown'][] = $field;
				continue;
			}
			$from = $prop['from'];
// print_r("field = $field, from = $from, orig_dbTable = $orig_dbTable, real_db = $real_db\n");
			if($from == $orig_dbTable){
// print_r("$from\n");
				$params['fields'][] = $dbTable.'.'.$field;
			}
			elseif(isset($this->options['linkTables']['one2one'][$from])){//one2one,应放在主Sql里，如果formatter是multi_row_edit或embedded_table，则另外处理
				$linkInfo = $this->options['linkTables']['one2one'][$from];
				$real_from = $linkInfo['real_db'].'.'.$linkInfo['table'];
// print_r("one2one, from = $from\n");				
				if($prop['formatter'] == 'multi_row_edit' || $prop['formatter'] == 'embed_table'){
					$this->moreInfoFields["one2one"][$real_from][] = $field;
				}
				else{
					$params['fields'][] = $real_from.'.'.$field;
					$rels['one2one'][$from] = $real_from;
				}
			}
			elseif(isset($this->options['linkTables']['m2m'][$from])){//m2m,应放在主Sql里
// print_r("m2m: $from\n");
				$linkInfo = $this->options['linkTables']['m2m'][$from];
				$real_from = $linkInfo['real_link_db'].'.'.$linkInfo['link_table'];
				$params['fields'][] = "GROUP_CONCAT(DISTINCT {$real_from}.{$linkInfo['link_field']}) AS $field";
				$rels['m2m'][$from] = $real_from;
			}
			elseif(isset($this->options['linkTables']['node_ver_m2m'][$from])){//node_ver_m2m,应放在主Sql里
// print_r("node_ver_m2m: $from\n");
				$linkInfo = $this->options['linkTables']['node_ver_m2m'][$from];
				$real_from = $linkInfo['real_link_db'].'.'.$linkInfo['link_table'];
				$params['fields'][] = "GROUP_CONCAT(DISTINCT {$real_from}.{$linkInfo['link_field']}) AS $field";
				$rels['node_ver_m2m'][$from] = $real_from;
			}
			elseif(isset($this->options['linkTables']['ver'][$from])){
// print_r("ver: $from\n");
				$linkInfo = $this->options['linkTables']['ver'][$from];
				$real_from = $linkInfo['real_db'].'.'.$linkInfo['table'];
				// $realField = substr($field, 0, -1);
				$params['fields'][] = "GROUP_CONCAT(DISTINCT $real_from.$field) AS $field";
				$rels['ver'][$from] = $real_from;
			}
			elseif(isset($this->options['linkTables']['one2m'][$from])){//one2m,应放在getmoreinfo里
// print_r("one2m: $from\n");
				$linkInfo = $this->options['linkTables']['one2m'][$from];
				$real_from = $linkInfo['real_db'].'.'.$linkInfo['table'];
				$this->moreInfoFields["one2m"][$real_from][] = $field;
				$rels['one2m'][$from] = $real_from;
			}
		}
// print_r($rels);		
		foreach($rels as $rel=>$relData){
			foreach($relData as $from=>$real_from){
				$linkInfo = $this->options['linkTables'][$rel][$from];
// print_r($linkInfo);		
// print_r($rel);		
				switch($rel){
					case 'one2one':
						$params['from'] .= " LEFT JOIN $real_from ON $dbTable.id=$real_from.{$linkInfo['self_link_field']}";
						break;
					case 'one2m':
					case 'm2m':
					case 'node_ver_m2m':
						$params['from'] .= " LEFT JOIN {$linkInfo['real_link_db']}.{$linkInfo['link_table']} ON {$linkInfo['real_link_db']}.{$linkInfo['link_table']}.{$linkInfo['self_link_field']}=$dbTable.id ";
						// " left join {$linkInfo['db']}.{$linkInfo['table']} on {$linkInfo['link_db']}.{$linkInfo['link_table']}.{$linkInfo['link_field']}={$linkInfo['db']}.{$linkInfo['table']}.id";
						$params['group'] = $dbTable.".id";
						break;
					case 'ver':
						$params['from'] .= " LEFT JOIN {$linkInfo['real_db']}.{$linkInfo['table']} ON {$linkInfo['real_db']}.{$linkInfo['table']}.{$linkInfo['self_link_field']}=$dbTable.id";
						$params['group'] = $dbTable.".id";
						break;
				}
			}
		}
// print_r($params);		
		$components = $this->tool->calcSql($params, $limited);
		$this->specialSql($special, $components);
// print_r($components);
// print_r($special);
// print_r($this->moreInfoFields);
		return $components;
	}
	
	public function getMoreInfoForRow($row){
		if(!empty($this->moreInfoFields)){
			$row = $this->getLinkedTableInfoForRow($row);
		}
		return $row;
	}
	
	protected function getLinkedTableInfoForRow($row){
// print_r($this->moreInfoFields);	
		foreach($this->moreInfoFields as $rel=>$relData){
// print_r("rel =  $rel, relDAta = ");
// print_r($relData);
			foreach($relData as $from=>$field){
				switch($rel){
					case 'one2one'://multi_row_edit, embed_table
						list($db, $table) = explode('.', $from);
						$linkInfo = $this->options['linkTables'][$rel][$from];
						// $fields = implode(',', $field);
						$this->tool->set_db($db);
						$res = $this->tool->query("SELECT * FROM $table WHERE $table.{$linkInfo['self_link_field']}={$row['id']}");
						$type_res = $res->fetch();
						$row[$linkInfo['table']] = $type_res;
						break;
					case 'one2m':
						list($db, $table) = explode('.', $from);
						$linkInfo = $this->options['linkTables'][$rel][$from];
// print_r($linkInfo);		
// print_r("SELECT * FROM $from WHERE $from.{$linkInfo['self_link_field']}={$row['id']}");
						// $fields = implode(',', $field);
						$params = array('db'=>$db, 'table'=>$table, $linkInfo['self_link_field']=>$row['id']);
			// print_r($params);
						$action_list = actionFactory::get(null, /*$this->controller,*/ 'list', $params);
						// $action_list->setParams($params);
						$ret = $action_list->handlePost();
						$row[$linkInfo['table']] = $ret['rows'];
// if($row['id'] == 135)						
// print_r($ret);
						// $res = $this->tool->query("SELECT * FROM $from WHERE $from.{$linkInfo['self_link_field']}={$row['id']}");
						// $type_res = $res->fetchAll();
						// $row[$linkInfo['table']] = $type_res;
						break;
					// case 'unknown':
						// $row = $this->getUnknownInfoForRow($row, $field);
						// break;
				}
			}
		}
		if(isset($this->moreInfoFields['unknown'])){
			$row = $this->getUnknownInfoForRow($row, $this->moreInfoFields['unknown']['unknown']);
		}
		return $row;
	}
		
	protected function getUnknownInfoForRow($row, $field){
		return $row;
	}
}

?>