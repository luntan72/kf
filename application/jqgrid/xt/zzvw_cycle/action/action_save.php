<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
// require_once('toolfactory.php');

class xt_zzvw_cycle_action_save extends action_save{	
	protected function setTool($tool_name = 'common'){
		$this->tool_name = $tool_name;
	}
	
	protected function handlePost(){
		$errorCode = array('code'=>ERROR_OK, 'msg');
    	$affectedID = 0;
		$realTable = $this->table_desc->get('real_table');
    	try{
    	    // save 
			if(!empty($this->params['new_zzvw_mcuauto_request_ids'])){
				$this->params['zzvw_mcuauto_request_ids'] = explode(",", $this->params['new_zzvw_mcuauto_request_ids']);
				unset($this->params['new_zzvw_mcuauto_request_ids']);
			}
			$errorCode = $this->beforeSave($this->db_name, $this->table_name, $this->params);
			if ($errorCode['code'] == ERROR_OK){
				$errorCode['msg'] = $affectedID = $this->save($this->db_name, $realTable, $this->params);
				$errorCode['file'] = $this->afterSave($affectedID);
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
		if($pair['cloneit'] == 'true')
			return $this->cloneit($db, $table, $pair);
		$pair = $this->prepare($db, $table, $pair);
		return $this->_saveOne($db, $table, $pair);
	}
	
	protected function cloneit($db, $table, $valuePairs){
		$params = $this->parseParams();
		$orig_id = $params['id'];
		
		$vs = $this->tool->extractData($valuePairs, $table, $db);
		$vs['isactive'] = ISACTIVE_ACTIVE;
		$vs['cloned_id'] = $orig_id;
		$vs['creater_id'] = $this->userInfo->id;
		$vs['cycle_status_id'] = CYCLE_STATUS_ONGOING;
					
		if($vs['creater_id'] != $this->userInfo->id){
			if(!empty($vs['tester_ids'])){
				$k = array_search($vs['creater_id'], $vs['tester_ids']);
				if($k !== false){
					unset($vs['tester_ids'][$k]);
					$vs['tester_ids'][] = $this->userInfo->id;
				}
			}
		}	
		
		if(!is_array($vs['prj_ids']))
			$vs['prj_ids'] = array($vs['prj_ids']);
		if(!is_array($vs['compiler_ids']))
			$vs['compiler_ids'] = array($vs['compiler_ids']);
		if(!is_array($vs['build_target_ids']))
			$vs['build_target_ids'] = array($vs['build_target_ids']);		
		
		// get multi fields
		// if new ones is more than old ones, then add additional new ones
		// if new ones is less than old ones, then add intersect ones
		$curParams = array();
		$curParams['new'] = array('prj_ids'=>$vs["prj_ids"], 'compiler_ids'=>$vs["compiler_ids"], 'build_target_ids'=>$vs["build_target_ids"]);
		if(in_array($vs['group_id'], array(GROUP_KSDK, GROUP_USB, GROUP_KIBBLE))){
			$tmp = $this->tool->getlinkFieldIds("cycle", $orig_id);
			// print_r($tmp);
			if(!empty($tmp["prj_id"]))
				$curParams['new']['prj_ids'] = array_intersect($vs["prj_ids"], $tmp["prj_id"]);
			
			if(!empty($tmp["compiler_id"]))
				$curParams['new']['compiler_ids'] = array_intersect($vs["compiler_ids"], $tmp["compiler_id"]);
			
			if(!empty($tmp["build_target_id"]))
				$curParams['new']['build_target_ids'] = array_intersect($vs["build_target_ids"], $tmp["build_target_id"]);
		}
		// save
		$affectedID = $this->_saveOne($db, $table, $vs);
		$searchCondition = $this->getSearchCondition($valuePairs, $orig_id );
		
		$from = "FROM cycle_detail";		
		$where = "WHERE cycle_detail.cycle_id = {$orig_id}";		
		if(!empty($vs['zzvw_mcuauto_request_ids']))	{
			$verInfo = "cycle_detail.testcase_ver_id";	
			$join = " LEFT JOIN testcase_ver ON testcase_ver.id = cycle_detail.testcase_ver_id".
				" LEFT JOIN testcase ON testcase.id = cycle_detail.testcase_id";	
		}
		else{
			$where .= " AND prj_testcase_ver.id IS NOT NULL";
			$verInfo = "prj_testcase_ver.testcase_ver_id";
			$join = "LEFT JOIN prj_testcase_ver ON prj_testcase_ver.testcase_id = cycle_detail.testcase_id".
				" LEFT JOIN testcase_ver ON testcase_ver.id = prj_testcase_ver.testcase_ver_id".
				" LEFT JOIN testcase ON testcase.id = cycle_detail.testcase_id";			
		}
		if(!empty($searchCondition)){
			foreach($searchCondition as $k=>$v)
				//	separate into two for codec: with streams and no streams
				if($k == "s_priority"){
					$s_where = $v." AND cycle_detail.codec_stream_id != 0";
					$s_join = " LEFT JOIN codec_stream ON codec_stream.id = cycle_detail.codec_stream_id";
				}
				elseif($k == "tc_priority"){
					//normally, no need to point out streams
					$tc_where = $v;
					//clone of codec testcase, should specific to no streams
					if(!empty($searchCondition["s_priority"]))
						$tc_where = $v." AND cycle_detail.codec_stream_id = 0";
				}
				else
					$where .= $v;
		}
		
		foreach($curParams['new']['prj_ids'] as $prj_id){
			foreach($curParams['new']['compiler_ids'] as $compiler_id){
				foreach($curParams['new']['build_target_ids'] as $build_target_id){
					//sql-base
					$tmpSql = "INSERT INTO cycle_detail (cycle_id, testcase_ver_id, testcase_id, codec_stream_id, test_env_id,".
						" build_result_id, tester_id, compiler_id, build_target_id, prj_id)".
						" SELECT {$affectedID}, {$verInfo}, cycle_detail.testcase_id, cycle_detail.codec_stream_id,".
						" cycle_detail.test_env_id, 1, cycle_detail.tester_id, cycle_detail.compiler_id, cycle_detail.build_target_id, {$prj_id}";
					
					//sql-where
					$tmpWhere = $where;
					if(!empty($vs['zzvw_mcuauto_request_ids']))	
						$tmpWhere .= " AND cycle_detail.prj_id = {$prj_id}";
					else
						$tmpWhere .= " AND prj_testcase_ver.prj_id = {$prj_id}";
					$tmpWhere .= " AND cycle_detail.compiler_id = {$compiler_id}".
						" AND cycle_detail.build_target_id = {$build_target_id}";
					$tmpWhere .= " AND testcase.isactive = ".ISACTIVE_ACTIVE.
						" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")";
						
					$sql = $tmpSql." ".$from." ".$join." ".$tmpWhere;
					if(!empty($tc_where))
						$sql = $sql.$tc_where;
					$this->tool->query($sql);
					if(!empty($s_where)){
						$sql = $tmpSql." ".$from." ".$join.$s_join." ".$tmpWhere.$s_where;
						$this->tool->query($sql);
					}
// print_r($sql);
				}
			}
		}		
		$curCases = array();
		if(!empty($vs['zzvw_mcuauto_request_ids'])){
			//update new ver for psdk & mqx & usb
			$res = $this->tool->query("SELECT DISTINCT testcase_id AS testcase_id FROM cycle_detail WHERE cycle_id={$affectedID}");
			while($row = $res->fetch()){
				$curCases[] = $row["testcase_id"];
				foreach($curParams['new']['prj_ids'] as $prj_id){
					$ver_sql = "SELECT prj_testcase_ver.testcase_ver_id FROM prj_testcase_ver". 
						" LEFT JOIN testcase_ver ON testcase_ver.id = prj_testcase_ver.testcase_ver_id".
						" LEFT JOIN testcase ON testcase.id = testcase_ver.testcase_id".
						" WHERE prj_testcase_ver.prj_id={$prj_id}".
						" AND prj_testcase_ver.testcase_id = {$row['testcase_id']}".
						" AND testcase.isactive=".ISACTIVE_ACTIVE.
						" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")";
					$ver_res = $this->tool->query($ver_sql);
					if($ver = $ver_res->fetch()){
						$this->tool->update("cycle_detail", array('testcase_ver_id'=>$ver['testcase_ver_id']), 
							"cycle_id={$affectedID} AND testcase_id={$row['testcase_id']} AND prj_id={$prj_id}");
					}
					else{
						$new_sql = "SELECT testcase_ver.id AS testcase_ver_id FROM testcase_ver".
							" LEFT JOIN prj_testcase_ver ON prj_testcase_ver.testcase_ver_id = testcase_ver.id".
							" LEFT JOIN testcase ON testcase.id = testcase_ver.testcase_id".
							" WHERE testcase_ver.testcase_id = {$row['testcase_id']}".
							" AND testcase.isactive=".ISACTIVE_ACTIVE.
							" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")".
							" AND prj_testcase_ver.id IS NOT NULL ORDER BY testcase_ver.id DESC";
						$new_res = $this->tool->query($new_sql);
						if($new_ver = $new_res->fetch()){
							$this->tool->update("cycle_detail", array('testcase_ver_id'=>$new_ver['testcase_ver_id']), 
								"cycle_id={$affectedID} AND testcase_id={$row['testcase_id']} AND prj_id={$prj_id}");
						}
					}
				}
			}
		}
		// if(!empty($curParams['last'])){		
			// // add new compier & target
			// $date = $this->tool->getMillisecond();
			// $sql = "INSERT INTO cycle_detail (cycle_id, testcase_ver_id, testcase_id, codec_stream_id, test_env_id,".
				// " build_result_id, tester_id, prj_id, compiler_id, build_target_id)".
				// " SELECT {$affectedID}, tmp_detail_{$date}.testcase_ver_id, tmp_detail_{$date}.testcase_id, tmp_detail_{$date}.codec_stream_id,".
				// " tmp_detail_{$date}.test_env_id, 1, tmp_detail_{$date}.tester_id";
			// if(!empty($curParams['last']["prj_ids"])){
				// $this->tool->query("CREATE TEMPORARY TABLE tmp_detail_{$date} ENGINE = HEAP AS SELECT * FROM cycle_detail WHERE cycle_id={$affectedID}");
				// foreach($curParams['last']["prj_ids"] as $prj_id){
					// $tmpSql = $sql;
					// $tmpSql .= ", {$prj_id}, tmp_detail_{$date}.compiler_id, tmp_detail_{$date}.build_target_id";
					// $tmpSql .= " FROM tmp_detail_{$date}";
					// $tmpSql .= " LEFT JOIN prj_testcase_ver ON prj_testcase_ver.testcase_id = tmp_detail_{$date}.testcase_id".
						// " AND prj_testcase_ver.prj_id={$prj_id}".
						// " LEFT JOIN testcase_ver ON testcase_ver.id = tmp_detail_{$date}.testcase_ver_id".
						// " LEFT JOIN testcase ON testcase.id = tmp_detail_{$date}.testcase_id".
						// " WHERE prj_testcase_ver.id IS NOT NULL AND testcase.isactive = ".ISACTIVE_ACTIVE.
						// " AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")";
					// $this->tool->query($tmpSql);
				// }
				// $this->tool->query("DROP TEMPORARY TABLE IF EXISTS tmp_detail_{$date}");
			// }
				
			// if(!empty($curParams['last']["compiler_ids"])){
				// $this->tool->query("CREATE TEMPORARY TABLE tmp_detail_{$date}  ENGINE = HEAP AS SELECT * FROM cycle_detail WHERE cycle_id={$affectedID}");
				// foreach($curParams['last']["compiler_ids"] as $compiler_id){
					// $tmpSql = $sql;
					// $tmpSql .= ", tmp_detail_{$date}.prj_id, {$compiler_id}, tmp_detail_{$date}.build_target_id";
					// $tmpSql .= " FROM tmp_detail_{$date}";
					// $this->tool->query($tmpSql);
				// }
				// $this->tool->query("DROP TEMPORARY TABLE IF EXISTS tmp_detail_{$date}");
			// }
			// if(!empty($curParams['last']["build_target_ids"])){
				// $this->tool->query("CREATE TEMPORARY TABLE tmp_detail_{$date}  ENGINE = HEAP AS SELECT * FROM cycle_detail WHERE cycle_id={$affectedID}");			
				// foreach($curParams['last']["build_target_ids"] as $build_target_id){
					// $tmpSql = $sql;
					// $tmpSql .= ", tmp_detail_{$date}.prj_id, tmp_detail_{$date}.compiler_id, {$build_target_id}";
					// $tmpSql .= " FROM tmp_detail_{$date}";
					// $this->tool->query($tmpSql);
				// }
				// $this->tool->query("DROP TEMPORARY TABLE IF EXISTS tmp_detail_{$date}");
			// }
		// }
		
		$this->processCycle($affectedID);
		$this->tool->log('save', $valuePairs);
		return $affectedID;
	}
	
	protected function afterSave($affectedID){
		$this->processLinkTables($affectedID);
		if($this->params['cloneit'] == 'true')
			return;
		if((empty($this->params['id']) || !empty($this->params['is_update_dp'])) && !empty($this->params['zzvw_mcuauto_request_ids'])){
			return $this->processDaPeng($affectedID);
		}
		if(!empty($this->params['template'])){
			$this->processTemplate($affectedID);
		}
    }
	
	private function processLinkTables($affectedID){
		$linkTables = $this->table_desc->getLinkTables();
		if(!empty($linkTables)){
			foreach($linkTables as $linkTable=>$linkInfo){
				if(in_array($linkTable, array("os", "chip", "board_type")))
					continue;
				$v = isset($this->params[$linkTable.'_ids']) ? $this->params[$linkTable.'_ids'] :
					(isset($this->params[$linkInfo['link_field']])?$this->params[$linkInfo['link_field']] :
					(isset($this->params[$linkInfo['link_field'].'s'])?$this->params[$linkInfo['link_field'].'s'] : null));
				if(!is_null($v)){
					if(is_string($v))
						$v = explode(',', $v);
					$this->tool->delete($linkInfo['link_table'], $linkInfo['self_link_field'].'='.$affectedID, $this->params['db']);
					foreach($v as $e){
						$this->tool->insert($linkInfo['link_table'], array($linkInfo['self_link_field']=>$affectedID, $linkInfo['link_field']=>$e), $this->params['db']);
					}
				}
			}
		}
	}
	
	protected function processDaPeng($affectedID){
		// $postData = array();
		// $postData = http_build_query($postData);
		// $opts = array('http' =>
			// array(
				// 'method'  => 'GET',
				// 'header'  => 'Content-type: application/x-www-form-urlencoded',
				// 'content' => $postData,
				// 'timeout' => 300
			// )
		// );
		// $context  = stream_context_create($opts);
		
		$board_type = $this->tool->getElementsName("board_type");
		$board_type[] = 'sdb';
		$board_type = implode("|", $board_type);
		
		// $zzvw_mcuauto_request_ids = explode(",", $this->params['zzvw_mcuauto_request_ids']);
		$zzvw_mcuauto_request_ids = $this->params['zzvw_mcuauto_request_ids'];
		$caseslist = array();
		$parse_result = array();
		
		$headers = array( 
			"Accept: application/json",  
			"Content-Type: application/json",
		);
		$curl_params = array(
			CURLOPT_URL => "",
			CURLOPT_HEADER => true, //为什么有httpheader但是header确实false？
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_VERBOSE => true, //如果设为true, 会打印所有过程信息
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			//CURLOPT_COOKIEJAR => $cookie_file
		);
		$ch = curl_init();  
		curl_setopt_array($ch, $curl_params); 			
		foreach($zzvw_mcuauto_request_ids as $zzvw_mcuauto_request_id){
			$url = 'http://10.192.225.198/dapeng/getRequestXT/'.trim($zzvw_mcuauto_request_id)."/";
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch); 
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$result = substr($result, $headerSize);
			if(!$result)continue;
			
			$parse_data = json_decode($result, true);
			$this->parseDpDetail($parse_data , $parse_result, $board_type);
			$caseslist = $this->tool->array_extends($caseslist, $parse_result['cases_ne']);
		}
		curl_close($ch);
		if(!empty($parse_result['detail']))
			$this->processDpDetail($affectedID, $parse_result['detail']);
		$this->processCycle($affectedID);
		return $this->processDpNoCases($caseslist, $affectedID);		
	}
	
	/* For Creating Cycle For Codec Team
	 */
	private function processTemplate($affectedID){
		$cycle_sql = "SELECT id, prj_ids, compiler_ids, build_target_ids, cycle.test_env_id, cycle.group_id FROM cycle WHERE cycle.id = ".$affectedID;
		$cycle_res = $this->tool->query($cycle_sql);
		$cycle = $cycle_res->fetch();
		if(!is_array($this->params['testcase_type_ids']))
			$this->params['testcase_type_ids'] = array($this->params['testcase_type_ids']);
		
		/* how to use the default template?		
		 * use the cycle_type to decide that if this cycle need to create a template
		 */		
		if($cycle['group_id'] == GROUP_CODEC || $cycle['group_id'] == GROUP_FAS ){
			$sql = $this->getTemplateSql();
			$curParams = array( 'affectedID'=>$affectedID );
			foreach($sql["cond"]['stream'] as $k=>$v){
				$i = 0;
				$csinfo = array();
				$res = $this->tool->query($sql["base"].$v);
				$curParams['tc_cond'] = $sql["cond"]['case'][$k];
				
				while($info = $res->fetch()){
					if($cycle['group_id'] == GROUP_FAS){
						if(!empty($info['testcase_ids']))
							$csinfo[$info['type']][$info['testcase_ids']][] = $info['codec_stream_id'];
					}
					else{
						if(!empty($info['trickmode_ids']))
							$csinfo[$i][$info['codec_stream_id']]= $info['trickmode_ids'];
						else{
							if(!empty($info['testcase_ids']))
								$csinfo[$i][$info['codec_stream_id']]= $info['testcase_ids'];
						}
					}
					$i++;
				}
				if(!empty($csinfo))
					$this->processTemplateDetail($csinfo, $curParams, $cycle);
			}	
			if($cycle['group_id'] == GROUP_CODEC){
				$cond['testcase'] = array(
					1=>array(" AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.")"),
					2=>array(" AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.")"),
					3=>array(" AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")")
				);
				$result = $this->tool->query("SELECT os.name AS name FROM prj LEFT JOIN os ON prj.os_id = os.id WHERE prj.id = {$cycle['prj_ids']}");
				if($os = $result->fetch()){
					if(stripos(strtolower($os['name']), "android") !== false){
						$testcase_module_ids = array();
						$modulelist = array('Webgl', 'Memory Leak', 'Multi-Instance', 'Encoder');
						foreach($modulelist as $module){
							$testcase_module_ids[] = $this->tool->getElementId("testcase_module", array('name'=>$module), array('name'));
						}	
						$curParams['testcase_module_ids'] = implode(",", $testcase_module_ids);
						$curParams['os'] = "android";
						$this->processTemplateDetail(array($cond['testcase'][$this->params['template']]), $curParams, $cycle, false);
					}	
					elseif(stripos(strtolower($os['name']), "linux") !== false){
						$this->processTemplateDetail(array($cond['testcase'][$this->params['template']]), $curParams, $cycle, false);
					}	
				}
			}
		}
	}
	
	private function parseDpParams(){
		if(isset($this->params['rel_name'])){
			$this->params['rel_id'] = $this->tool->getExistedId("rel", array("name"=>$this->params['rel_name']));
			if($this->params['rel_id'] == "error")
				$this->params['rel_id'] = 0;
			unset($this->params['rel_name']);
		}
		if(isset($this->params['creater'])){
			//get creater_id
			$res = $this->log_db->query("SELECT id FROM users WHERE username='".$this->params['creater']."'");
			if($row = $res->fetch()){
				$this->params['creater_id'] = $row['id'];
				$this->params['tester_ids'] = $row['creater_id'];
			}
			else{
				$this->params['creater_id'] = 0;
				$this->params['tester_ids'] = $row['creater_id'];
			}
			unset($this->params['creater']);
		}
		if(isset($this->params['name'])){
			//to figure out if this name is unique or not
			$res = $this->tool->query("SELECT id FROM cycle WHERE name='".$this->params['name']."'");
			if($row = $res->fetch()){
				// return "This Name Is Not Unique. Please Modify it to Make it Unique";
				return $row['id'];
			}
		}
		$this->params['cloneit'] = "false";
		return 0;
	}
	
	protected function parseDpDetail($parse_data, &$parse_result, $board_type ){		
		$curParams = array();
		$cases_ne = array();
		static $info = array();
		
		// parse variables
		$curParams = array(
			'header'=>$parse_data['header'],
			'detail'=>$parse_data['detail'],
			'tool_type'=>trim($parse_data['header']['tool']),
			'test_env_id'=>1,
			'test_env_items'=>explode(",", trim($parse_data['header']['taglist'])),
			'releaseversion'=>trim($parse_data['header']['releaseversion'])
			
		);
		
		// get os version
		$curParams['suffix'] = "";
		if($curParams['releaseversion'] == "2" && $curParams['tool_type'] != "KSDK-USB")
			$curParams['suffix'] = "_2.0";
		
		// get usb test env
		if($curParams['tool_type'] == "KSDK-USB" && in_array(1, $curParams['test_env_items'])){
			$test_env = 'EHCI';
			$curParams['test_env_id'] = $this->tool->getElementId("test_env", array('name'=>$test_env));
		}	
		
		// get default os & testcase_type
		$os_testcase_type = $this->getOsTcType($curParams['tool_type']);
		$curParams = array_merge($curParams, $os_testcase_type);
		if('error' == $curParams['testcase_type_id'])return;

		foreach($curParams["detail"] as $detail){
			// $new_name = '';
			// $default_os = $new_os = $os;
			// $prj_id = $testcase_id = 'error';
			// $detail->testcasename = trim($detail->testcasename);
			// $detail->platform = trim($detail->platform);
			$tmp = array(
				'new_os'=>$curParams['os'],
				'new_name'=>"",
				'prj_id'=>"error",
				'testcase_id'=>"error",
				'platform'=>trim($detail['platform']),
				'default_os'=>$curParams['os'],
				'target'=>trim($detail['target']),
				'compiler'=>trim($detail['compiler']),
				'testcasename'=>trim($detail['testcasename'])
			);
			
			// proess os cases, add ksdk_osname as a new os,testcasename may change, save before that
			$this->getDpOs($curParams, $tmp, $info);
			
			//process prj, board_type, chip_type, os, chip_type_os, board_type_chip_type
			if(!isset($info['os_pf'][$tmp['platform']][$tmp['default_os']])){
				$info['os_pf'][$tmp['platform']][$tmp['default_os']] = $this->processDpPrj($board_type, $tmp['platform'], $tmp['default_os']);
			}
			$tmp['prj_id'] = $info['os_pf'][$tmp['platform']][$tmp['default_os']]['prj_id'];
			$tmp['os_id'] = $info['os_pf'][$tmp['platform']][$tmp['default_os']]['os_id'];
			if($tmp['prj_id'] == 'error'){
				if(!isset($cases_ne['prjs'][$tmp['platform']." with ".$tmp['default_os']])){
					$cases_ne['prjs'][$tmp['platform']." with ".$tmp['default_os']] = $tmp['platform']." with ".$tmp['default_os'];
				}
				continue;
			}
			
			//process testcase, testcasename may be modified, save before that
			$this->getDpCase($curParams, $tmp, $info);
// print_r($tmp['testcase_id']."\n");			
// print_r($tmp['testcasename']."\n");
			if($tmp['testcase_id'] == 'error'){
				if(!isset($cases_ne['cases'][$tmp['platform']." with ".$tmp['default_os']][$tmp['testcasename']])){
					$cases_ne['cases'][$tmp['platform']." with ".$tmp['default_os']][$tmp['testcasename']] = $tmp['testcasename'];
				}
				continue;
			}
			
			//get proper testcase_ver_id
			$tmp['testcase_ver_id'] = $this->getCaseVer($tmp);			
			
			//process target, target-os
			$this->getDpCompilerTarget($tmp, $info);
			
			//process result
			if(!isset($info['bres'][$detail['buildresult']])){
				$info['bres'][$detail['buildresult']] = $this->tool->getResultId($detail['buildresult']);
			}
			$build_result_id = $info['bres'][$detail['buildresult']];
			if(!isset($info['rres'][$detail['runresult']])){
				$info['rres'][$detail['runresult']] = $this->tool->getResultId($detail['runresult']);
			}
			$result_type_id = $info['rres'][$detail['runresult']];
			if(!isset($parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']])){
				if(trim($detail['runendtime']) == 'None' || empty($detail['runendtime']))
					$detail['runendtime'] = 0;
				$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['finish_time'] = $detail['runendtime'];
				$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['result_type_id'] = $result_type_id;
				$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['build_result_id'] = $build_result_id;
				$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['dp_detail_id'] = $detail['id'];
				$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['jira_key_ids'] = trim($detail['jiraid']);

			}
			else{
				if(trim($detail['runendtime']) == 'None' || empty($detail['runendtime']))
					$detail['runendtime'] = 0;
				if(($detail['runendtime'] > $parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['finish_time']) || 
					(0 == $detail['runendtime'] && $detail['runendtime'] == $parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['finish_time'])){				
					$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['finish_time'] = $detail['runendtime'];
					$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['result_type_id'] = $result_type_id;
					$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['build_result_id'] = $build_result_id;
					$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['dp_detail_id'] = $detail['id'];
					$parse_result['detail'][$tmp['testcase_ver_id']][$tmp['testcase_id']][$tmp['prj_id']][$tmp['compiler_id']][$tmp['build_target_id']][$curParams['test_env_id']]['jira_key_ids'] = trim($detail['jiraid']);
				}
			}
		}
		$parse_result['cases_ne'] = $cases_ne;
	}
	
	private function getDpOs($params1, &$params2, &$info){
		$casename = $params2['testcasename'];
		if(!isset($info['os_case'][$casename])){
			if("KSDK_bm" == $params2['new_os']){
				if(preg_match("/^(.*)_(".$params1["os_type"].")$/i", $params2['testcasename'], $matches)){
					$params2['testcasename'] = $matches[1];
					$params2['new_os'] = 'KSDK_'.strtolower($matches[2]);
					$params2['default_os'] = $params2['new_os'].$params1['suffix'];
				}
				else if(preg_match("/^(.*)_with_(".$params1["os_type"].")$/i", $params2['testcasename'], $matches)){
					$params2['testcasename'] = $matches[1];
					$params2['new_os'] = 'KSDK_'.strtolower($matches[2]);
					$params2['default_os'] = $params2['new_os'].$params1['suffix'];
				}
				else if(preg_match("/^(.*)_(".$params1["os_type"].")_(master|slave)$/i", $params2['testcasename'], $matches)){
					$params2['new_os'] = 'KSDK_'.strtolower($matches[2]);
					$params2['default_os'] = $params2['new_os'].$params1['suffix'];
				}
				else if(preg_match("/^(.*)_(".$params1["os_type"].")_(example)$/i", $params2['testcasename'], $matches)){
					$params2['testcasename'] = $matches[1];
					$params2['new_os'] = 'KSDK_'.strtolower($matches[2]);
					$params2['default_os'] = $params2['new_os'].$params1['suffix'];
				}
				else{
					$params2['new_os'] = $params2['new_os'];
					$params2['default_os'] = $params2['new_os'].$params1['suffix'];
				}
			}
			$info['os_case'][$casename] = 
				array('testcasename'=>$params2['testcasename'], 'default_os'=>$params2['default_os'], 'new_os'=>$params2['new_os']);
		}
		else{
			$params2['testcasename'] = $info['os_case'][$casename]['testcasename'];	
			$params2['default_os'] = $info['os_case'][$casename]['default_os'];
			$params2['new_os'] = $info['os_case'][$casename]['new_os'];	
		}
	}
	
	private function getDpCase($params1, &$params2, &$info){
		$casename = $params2['testcasename'];
		$info['cases'][$casename] = "error";
		switch($params1['tool_type']){
			case 'KSDK-USB':
				$element = array('associated_code'=>$params2['testcasename']);
				break;
			case 'KSDK-MQX-OOBE':
				//how to match cases???
				if(preg_match("/^app-mqx_(.*)$/i", $params2['testcasename'], $matches)){
					$params2['testcasename'] = $matches[1];
				}
				else if(preg_match("/^app-osa_(.*)$/i", $params2['testcasename'], $matches)){
					$tmp['testcasename'] = $matches[1];
				}
				$element = array('associated_summary'=>$params2['testcasename']);
				break;
			case 'MQX-OOBE':
				if(preg_match('/^mqx_(.*)$/i', $params2['testcasename'], $matches)){
					$params2['testcasename'] = $matches[1];
				}
				$element = array('associated_summary'=>$params2['testcasename']);
				break;
			default:
				$element = array('associated_summary'=>$params2['testcasename']);
				break;
		}
		/* Since use summary to find a unique cases,
		 * so when filter a case must add testcase type to the query conditions
		 */
		$element['testcase_type_id'] = $params1['testcase_type_id'];
		$sql = "SELECT testcase_ver.testcase_id as testcase_id FROM testcase_ver".
			" LEFT JOIN testcase ON testcase.id=testcase_ver.testcase_id";
		if(!empty($element['associated_summary']))
			$sql .= " WHERE testcase_ver.associated_summary='".$element['associated_summary'];
		else if(!empty($element['associated_code']))
			$sql .= " WHERE testcase_ver.associated_code='".$element['associated_code'];
		$sql .= "' AND testcase.testcase_type_id=".$element['testcase_type_id']." LIMIT 1";
		$res = $this->tool->query($sql);
		if($row = $res->fetch()){
			$info['cases'][$casename] = $row["testcase_id"];
		}
		else{
			$info['cases'][$casename] = "error";
		}					
		$params2['testcase_id'] = $info['cases'][$casename];
	}
	
	private function getCaseVer($params){
		/* To find wether the cases link to the specific project,
		 * if there is, then get the specific ver to use,
		 * if there is not, then get the latest published or golden ver to use
		 */
		$testcase_ver_id = 0;
		$sql = "SELECT testcase_ver_id FROM prj_testcase_ver". 
			" left join testcase_ver on testcase_ver.id = prj_testcase_ver.testcase_ver_id".
			" left join testcase on testcase.id = testcase_ver.testcase_id".
			" WHERE prj_testcase_ver.prj_id={$params['prj_id']}".
			" AND prj_testcase_ver.testcase_id={$params['testcase_id']}".
			" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")".
			" AND testcase.isactive = ".ISACTIVE_ACTIVE;
		$res = $this->tool->query($sql);
		if($row = $res->fetch())
			$testcase_ver_id = $row['testcase_ver_id'];
		else{
			$result = $this->tool->query("select id from testcase_ver where testcase_id = {$params['testcase_id']} order by id DESC");
			if($rrow = $result->fetch())
				$testcase_ver_id = $rrow['id'];
		}
		return $testcase_ver_id;
	}
	
	private function getDpCompilerTarget(&$params, &$info){
		if(preg_match('/^(.*)-(ram|ddr|ddrdata|flash)$/i', $params['target'], $matches)){
			$params['target'] = $matches[2]."_".$matches[1];
		}
		else if(preg_match('/^(.*)-(.*)(ram|ddr|ddrdata|flash)$/i', $params['target'], $matches)){
			$params['target'] = $matches[2]."_".$matches[3]."_".$matches[1];
		}
		else if(preg_match('/^(.*)-(.*)(ram|ddr|ddrdata|flash)-(.*)$/i', $params['target'], $matches)){
			$params['target'] = $matches[2]."_".$matches[3]."_".$matches[4]."_".$matches[1];
		}
		if(!isset($info['os_target'][$params['target']][$params['os_id']])){
			$info['os_target'][$params['target']][$params['os_id']] = 
				$this->tool->getElementId("build_target", array('name'=>ucwords($params['target'])), array('name'));
			$this->tool->getElementId('build_target_os', 
				array('build_target_id'=>$info['os_target'][$params['target']][$params['os_id']], 'os_id'=>$params['os_id']));
		}
		$params['build_target_id'] = $info['os_target'][$params['target']][$params['os_id']];
		
		//process compiler, compiler-os
		if(!isset($info['os_target'][$params['compiler']][$params['os_id']])){
			$info['os_compiler'][$params['compiler']][$params['os_id']] = 
				$this->tool->getElementId("compiler", array('name'=>strtoupper($params['compiler'])), array('name'));
			$this->tool->getElementId('compiler_os', 
				array('compiler_id'=>$info['os_compiler'][$params['compiler']][$params['os_id']], 'os_id'=>$params['os_id']));
		}
		$params['compiler_id'] = $info['os_compiler'][$params['compiler']][$params['os_id']];
	}
	
	protected function processDpDetail($affectedID, $detail){
		$insert = $last_insert = array();
		$date_insert = $date_update = $date_last_insert = $date_last_update = $last1 = $last2 = 0;
		// $this->tool->query("CREATE TEMPORARY TABLE `cycle_detail_dp_tmp` ENGINE = HEAP".
			// " SELECT `id`, `prj_id`, `testcase_id`, `compiler_id`, `build_target_id`, `codec_stream_id`,".
			// " `cycle_id`, `test_env_id`, `tester_id`, `result_type_id`, `build_result_id`, `dp_detailid`".
			// " FROM `cycle_detail` WHERE `cycle_id` = {$affectedID}");
		$key = $this->tool->getElementId("log_key", array("server"=>"dapeng", "directory"=>"dapeng/showMcuautoRequestDetail"));
		foreach($detail as $testcase_ver_id=>$testcaseInfo){
			foreach($testcaseInfo as $testcase_id=>$verInfo){
				foreach($verInfo as $prj_id=>$prjInfo){
					foreach($prjInfo as $compiler_id=>$cpInfo){
						foreach($cpInfo as $build_target_id=>$envInfo){
							foreach($envInfo as $test_env_id=>$resultInfo){
								$update = array();
								$result_type_id = $resultInfo['result_type_id'];
								$build_result_id = $resultInfo['build_result_id'];
								$finish_time = $resultInfo['finish_time'];
								$dp_detail_id = $resultInfo['dp_detail_id'];
								$jira_key_ids = $resultInfo['jira_key_ids'];

								$cond = "cycle_id = $affectedID AND prj_id = $prj_id AND testcase_id = $testcase_id AND compiler_id = $compiler_id AND 
									build_target_id = $build_target_id AND codec_stream_id = 0 AND test_env_id = {$test_env_id}";
								// $res = $this->tool->query("SELECT id, tester_id, result_type_id, build_result_id, dp_detailid FROM cycle_detail_dp_tmp WHERE ".$cond." LIMIT 1");
								$res = $this->tool->query("SELECT id, tester_id, result_type_id, build_result_id, logs FROM cycle_detail WHERE ".$cond." LIMIT 1");
								if($row = $res->fetch()){
									$row["logs"] = json_decode($row["logs"], true);
									if(($row['tester_id'] == TESTER_DP) && ($row['result_type_id'] != $result_type_id || $row['build_result_id'] != $build_result_id || 
										empty($row["logs"]) || (isset($row["logs"][$key]) && !in_array($dp_detail_id, $row["logs"][$key])))){
										// $update['tester_id'] = TESTER_DP;
										if($row['build_result_id'] != $build_result_id){
											$update['build_result_id'] = $build_result_id;
										}
										if(!empty($row["logs"]) && isset($row["logs"][$key]) && !in_array($dp_detail_id, $row["logs"][$key])){
											$row["logs"][$key] = array($dp_detail_id);
											$update['logs'] = $row["logs"];
										}
										elseif(empty($row["logs"]) || !isset($row["logs"][$key])){
											$update['logs'][$key] = array($dp_detail_id);
										}
										if(!empty($update['logs']))
											$update['logs'] = json_encode($update['logs']);
										
										if($row['result_type_id'] != $result_type_id){
											$update['result_type_id'] = $result_type_id;
											$update['finish_time'] = $finish_time;
										}
										// $update['jira_key_ids'] = $jira_key_ids;
										if ($jira_key_ids != "0")
											$update['defect_ids'] = $jira_key_ids;
										
										$this->tool->update('cycle_detail', $update, 'id='.$row['id']);
										if($row['result_type_id'] != $result_type_id){
											$tcres = $this->tool->query("SELECT id, cycle_detail_id FROM testcase_last_result WHERE testcase_id={$testcase_id}".
												" AND prj_id={$prj_id} AND rel_id={$this->params['rel_id']} AND codec_stream_id=0 LIMIT 1");
											$last = array('result_type_id'=>$result_type_id, 'cycle_detail_id'=>$row['id'], 'tested'=>$finish_time);
											if($data = $tcres->fetch()){
												if( $row['id'] != $data['cycle_detail_id']){
													$this->tool->update('testcase_last_result', $last, "id=".$data['id']);
												}
											}
											else{
												$this->tool->insert('testcase_last_result', $last);
											}
											$this->tool->update('testcase', array('last_run'=>$finish_time), "id=".$testcase_id);
										}
									}
									elseif(!empty($row['logs']) && isset($row["logs"][$key]) && $row['tester_id'] != TESTER_DP && !in_array($dp_detail_id, $row["logs"][$key])){
										$row["logs"][$key] = array($dp_detail_id);
										$row["logs"] = json_encode($row["logs"]);
										$update = array('logs'=>$row["logs"]);
										$this->tool->update('cycle_detail', $update, 'id='.$row['id']);
									}
								}
								else{
									$logs = json_encode(array($key=>array($dp_detail_id)));
									$insert = array('prj_id'=>$prj_id, 'testcase_id'=>$testcase_id, 'testcase_ver_id'=>$testcase_ver_id, 'compiler_id'=>$compiler_id, 
										'build_target_id'=>$build_target_id, 'codec_stream_id'=>0, 'cycle_id'=>$affectedID, 'test_env_id'=>$test_env_id,
										'build_result_id'=>$build_result_id, 'result_type_id'=>$result_type_id, 'finish_time'=>$finish_time, 'tester_id'=>TESTER_DP,
										'logs'=>$logs);//TESTER_DP=>dapeng
									$detailID = $this->tool->insert("cycle_detail", $insert);
									$tcres = $this->tool->query("SELECT id, cycle_detail_id FROM testcase_last_result WHERE testcase_id={$testcase_id}".
										" AND prj_id={$prj_id} AND rel_id={$this->params['rel_id']} AND codec_stream_id=0 LIMIT 1");
									$last = array('result_type_id'=>$result_type_id, 'cycle_detail_id'=>$detailID, 'tested'=>$finish_time);
									if($data = $tcres->fetch()){
										if($detailID != $data['cycle_detail_id']){
											$this->tool->update('testcase_last_result', $last, "id=".$data['id']);
										}
									}
									else{
										$this->tool->insert('testcase_last_result', $last);
									}
									$this->tool->update('testcase', array('last_run'=>$finish_time), "id=".$testcase_id);
								}
							}
						}
					}
				}
			}
		}
		// $this->tool->query("DROP TABLE `cycle_detail_dp_tmp`");
	}
	
	private function getOsTcType($testcase_type){
		$os_type = array();
		if(stripos($testcase_type, "KSDK-") !== false){
			$os = "KSDK_bm";
			$testcase_type = $testcase_type;
			if(stripos($testcase_type, "USB") !== false)
				$testcase_type = 'USB';
			$res = $this->tool->query("SELECT id, name FROM os");
			while($row = $res->fetch()){
				if($row['name'] != 'N/A'){
					if(preg_match("/^KSDK_(.*)$/i", $row['name'], $matches))
						$oses[$row['id']] = strtolower($matches[1]);
					else
						$oses[$row['id']] = strtolower($row['name']);
				}
			}
			$os_type = implode("|", $oses);
		}
		else if(stripos($testcase_type, "KSDK-MQX-") !== false){
				$os = "KSDK_mqx";
				$testcase_type = 'MQX';
		}
		else if(stripos($testcase_type, "MQX-") !== false){
			$os = "MQX";
			$testcase_type = 'MQX';
		}
		$testcase_type_id = $this->tool->getExistedId("testcase_type", array('name'=>$testcase_type), array('name'));
		return array('os'=>$os, 'os_type'=>$os_type, 'testcase_type_id'=>$testcase_type_id);
	}
	
	private function processTemplateDetail($info, $params1, $params2, $isStream = true){
		foreach($info as $data){
			foreach($data as $key=>$value){
				// structure of two parse $info is different
				// bat, func and full of trickmodes and streams
				if($isStream){
					if($params2['group_id'] == GROUP_FAS){
						$params1['streaminfo'] = $value;
						$params1['testcase_ids'] = $key;
					}			
					else{
						if(empty($value))continue;
						$params1['testcase_ids'] = $value;
						$params1['streaminfo'] = array($key);
						if(preg_match("/^,(.*)$/", $params1['testcase_ids'], $matches))
							$params1['testcase_ids'] = $matches[1];
					}
				}
				else{
					// bat, func and full of testcases
					$params1['tc_cond'] = $value;
					$params1['streaminfo'] = array("0");
					$params1['testcase_ids'] = "";
				}
				
				$key_fields = array('cycle_id', 'test_env_id', 'testcase_id', 'prj_id', 'compiler_id', 'build_target_id', 'codec_stream_id');				
				$sql = "SELECT ptv.prj_id, ptv.testcase_id, ptv.testcase_ver_id FROM prj_testcase_ver ptv".
					" LEFT JOIN testcase_ver ver ON ver.id = ptv.testcase_ver_id".
					" LEFT JOIN testcase ON testcase.id = ptv.testcase_id";
					
				$where = " WHERE ptv.prj_id IN ({$params2['prj_ids']})";
				if(!empty($params1['tc_cond']))
					$where = $where.$params1['tc_cond'];
				if(!empty($params1['testcase_ids']))
					$where = $where." AND ptv.testcase_id IN ({$params1['testcase_ids']})";
				if(!empty($params1['os']) && $params1['os'] == "android")
					$where = $where." AND testcase.testcase_module_id in ( {$params1['testcase_module_ids']} )";				
				$where = $where." AND testcase.testcase_type_id IN (".implode(",", $this->params['testcase_type_ids']).")".
					" AND ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")".
					" AND testcase.isactive = ".ISACTIVE_ACTIVE;
					
				$res = $this->tool->query($sql.$where);
				while($case = $res->fetch()){
					if(empty($case['testcase_ver_id']))continue;
					$detailinfo = array('cycle_id'=>$params1['affectedID'], 'test_env_id'=>$params2['test_env_id'], 'testcase_id'=>$case['testcase_id'],
						'testcase_ver_id'=>$case['testcase_ver_id'], 'prj_id'=>$case['prj_id'], 'compiler_id'=>$params2['compiler_ids'], 
						'build_target_id'=>$params2['build_target_ids']);
					$streaminfo = array_unique($params1['streaminfo']);
					foreach($streaminfo as $streamid){
						$detailinfo['codec_stream_id'] = $streamid;
						$this->tool->getElementId('cycle_detail', $detailinfo, $key_fields);
					}
				}
			}
		}
	}
	
	private function getTemplateSql(){
		$cond = array();
		$res = $this->tool->query("SELECT name, element_ids FROM tag WHERE id = ".$this->params['tag']);
		$row = $res->fetch();
		if(stripos($row['element_ids'], ",") === 0)
			$row['element_ids'] = substr($row['element_ids'], 1, -1);
		$sql = "SELECT distinct stream.id AS codec_stream_id, stream.testcase_ids AS trickmode_ids,".
			" type.name AS type, type.testcase_ids as testcase_ids".
			" FROM codec_stream stream".
			" LEFT JOIN codec_stream_type type ON type.id = stream.codec_stream_type_id".
			" WHERE stream.id IN (".$row['element_ids'].")". 
			" AND stream.isactive = ".ISACTIVE_ACTIVE. 
			" AND stream.codec_stream_format_id != ".STREAM_FORMAT_CUSTOM.
			" AND stream.codec_stream_type_id != ".STREAM_TYPE_UNKNOWN;
		switch($this->params['template']){
			case 1://BAT
				$cond['stream'][0] = " AND stream.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.")";
				$cond['case'][0] = " AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")";
				break;
			case 2: //FUNCTION
				$cond['stream'][0] = " AND stream.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.")";
				$cond['stream'][1]  = " AND stream.testcase_priority_id IN (".TESTCASE_PRIORITY_P2.")";
				$cond['case'][0]  = " AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")";
				$cond['case'][1]  = " AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.")";
				break;
			case 3: //FULL
				$cond['stream'][0] = " AND stream.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.")";
				$cond['stream'][1]  = " AND stream.testcase_priority_id IN (".TESTCASE_PRIORITY_P2.")";
				$cond['stream'][2]  = " AND stream.testcase_priority_id IN (".TESTCASE_PRIORITY_P3.")";
				$cond['case'][0]  = " AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")";
				$cond['case'][1]  = " AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.",".TESTCASE_PRIORITY_P2.",".TESTCASE_PRIORITY_P3.")";
				$cond['case'][2]  = " AND ver.testcase_priority_id IN (".TESTCASE_PRIORITY_P1.")";
				break;
		}
		return array("base"=>$sql, "cond"=>$cond);
	}
							
	protected function updateTestCaseVer($cycle_id, $prj_id){
		if(!empty($prj_id)){
			$sql = "SELECT DISTINCT testcase_id AS testcase_id FROM cycle_detail WHERE cycle_id={$cycle_id} AND prj_id = {$prj_id}";
			$res = $this->tool->query($sql);
			while($row = $res->fetch()){
				$ver_sql = "SELECT testcase_ver_id FROM prj_testcase_ver". 
					" LEFT JOIN testcase_ver ON testcase_ver.id = prj_testcase_ver.testcase_ver_id".
					" WHERE prj_testcase_ver.prj_id=$prj_id".
					" AND prj_testcase_ver.testcase_id = {$row['testcase_id']}".
					" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")";
				$ver_res = $this->tool->query($ver_sql);
				$ver = $ver_res->fetch();
				if($ver){
					$data = array('testcase_ver_id'=>$ver['testcase_ver_id']);
					$this->tool->update('cycle_detail', $data, "cycle_id=".$cycle_id." AND testcase_id=".$row['testcase_id']);
				}
			}
		}
	}
	
	
	private function processDpPrj($board_type, $platform, $os){
		$prj_id = 'error';
		$os_id = $this->tool->getElementId('os', array('name'=>$os));
		if(preg_match("/^(".$board_type.")(.*)$/i", strtolower($platform), $matches)){//PSDK
			$board_type_id = $this->tool->getElementId("board_type", array('name'=>strtoupper(trim($matches[1]))), array('name'));
			$chip = trim($matches[2]);
			$chip = strtoupper($chip);
			if(stripos($chip, "i") === 0)
				$chip = lcfirst($chip);
			$chip_id = $this->tool->getChipId($chip, array("os_id"=>$os_id, "board_type_id"=>$board_type_id));	
			if(('error' == $chip_id) && isset($mt[2]) && ('512r' == $mt[2]  || '512' == $mt[2])){
				$chip = $chip."_".$mt[2];
				$chip_id = $this->$this->tool->getChipId($chip, array("os_id"=>$os_id, "board_type_id"=>$board_type_id));
			}
			$name = $chip."-".strtoupper(trim($matches[1]))."-".$os;
			$prj_id = $this->tool->getExistedId("prj", array('name'=>$name, 'os_id'=>$os_id, 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id),
				array('os_id', 'chip_id', 'board_type_id'));
		}
		else if(preg_match("/^(.*?)_(".$board_type.")_(.*)$/i", $platform, $matches)){
			$chip = trim($matches[1]);
			$chip = str_replace('imx', 'i.MX', strtolower($chip));
			if($matches[3] == 'm4' || $matches[3] == 'a5')
				$chip .= "_".$matches[3];
			if('sdb' == trim(strtolower($matches[2])))
				$matches[2] = 'Sabre_SDB';
			$board_type_id = $this->tool->getElementId("board_type", array('name'=>trim($matches[2])), array('name'));
			$chip_id = $this->tool->getChipId($chip, array("os_id"=>$os_id, "board_type_id"=>$board_type_id));
			$prj_id = $this->tool->getExistedId("prj", array('os_id'=>$os_id, 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id));
		}
		return array('prj_id'=>$prj_id, 'os_id'=>$os_id);
	}
	
	private function getSearchCondition($valuePairs, $cycle_id){
		$searchCondition = array();
		$specialGroups = array(GROUP_CODEC, GROUP_FAS);
		$res = $this->tool->query("select id, name from result_type");
		while($info = $res->fetch()){
			$info['name'] = strtolower($info['name']);
			$result_type[$info['name']] = $info['id'];
		}
		$tmp = array();
		$tmp['results'] = array();
		foreach($valuePairs['case_choose'] as $k=>$v){
			$key = $k;
			$v = strtolower($v);
			switch($v){
				case "all":
					$searchCondition[$v] = "";
					break;
				case "p1":
					$tmp['priority']['testcase'][$v] = TESTCASE_PRIORITY_P1;//codec_stream_id == 0
					if(in_array($valuePairs['group_id'], $specialGroups))
						$tmp['priority']['stream'][$v] = TESTCASE_PRIORITY_P1;// codec_stream_id != 0
					break;
				case "p2":
					$tmp['priority']['testcase'][$v] = TESTCASE_PRIORITY_P2;
					if(in_array($valuePairs['group_id'], $specialGroups))
						$tmp['priority']['stream'][$v] = TESTCASE_PRIORITY_P2;
					break;
				case "pass":
					$tmp['results'][$v] = $result_type[$v];
					break;
				case "fail":
					$tmp['results'][$v] = $result_type[$v];
					break;
				case "n/t":
					$tmp['results'][$v] = $result_type[$v];
					break;
				case "n/s":
					$tmp['results'][$v] = $result_type[$v];
					break;
				case "n/a":
					$tmp['results'][$v] = $result_type[$v];
					break;
				case "skip":
					$tmp['results'][$v] = $result_type[$v];
					break;
				case "blank":
					$tmp['results'][$v] = 0;
					break;
			}
		}
		if(!empty($tmp['results'])){
// print_r($tmp['results']);
			$searchCondition['result'] = " AND cycle_detail.result_type_id IN (".implode(",", $tmp['results']).")";
// print_r("\n".$searchCondition['result']."\n");
		}
		
		if(!empty($tmp['priority']['testcase'])){
			$searchCondition['tc_priority'] = " AND testcase_ver.testcase_priority_id IN (".implode(",", $tmp['priority']['testcase']).")";
		}
		if(!empty($tmp['priority']['stream'])){
			$searchCondition['s_priority'] = " AND codec_stream.testcase_priority_id IN (".implode(",", $tmp['priority']['stream']).")";
		}
		return $searchCondition;
	}
	
	private function processCycle($cycle_id){
		$sql = "SELECT GROUP_CONCAT(DISTINCT detail.prj_id) AS prj_ids, GROUP_CONCAT(DISTINCT detail.compiler_id) AS compiler_ids,". 
			" GROUP_CONCAT(DISTINCT detail.build_target_id) AS build_target_ids, GROUP_CONCAT(DISTINCT tc.testcase_type_id) AS testcase_type_ids,".
			" GROUP_CONCAT(DISTINCT detail.tester_id) AS tester_ids, GROUP_CONCAT(DISTINCT test_env_id) AS test_env_ids".
			" FROM cycle_detail detail".
			" LEFT JOIN testcase tc ON tc.id = detail.testcase_id". 
			" WHERE detail.cycle_id = $cycle_id";
		$res = $this->tool->query($sql);
		if($row = $res->fetch()){
			if(!empty($row['prj_ids'])){
				// update cycle info here
				$this->tool->update("cycle", array('prj_ids'=>$row['prj_ids'], 'compiler_ids'=>$row['compiler_ids'], 'build_target_ids'=>$row['build_target_ids'],
					'testcase_type_ids'=>$row['testcase_type_ids'], 'test_env_id'=>1, 'tester_ids'=>$row['tester_ids']), 'id='.$cycle_id);
				// update link table
				$data = array("prj_id"=>$row['prj_ids'], 'compiler_id'=>$row['compiler_ids'], 'build_target_id'=>$row['build_target_ids'],
					"testcase_type_id"=>$row['testcase_type_ids'], "tester_id"=>$row['tester_ids']);
				$this->tool->updateLinkTables(array("cycle_id"=>$cycle_id), $data);
			}
		}
	}
	
	private function processDpNoCases($no_cases, $affectedID){
		$fileName = REPORT_ROOT."/download/dapeng/no_cases_".$affectedID.".yml";
		if(file_exists($fileName)){//should delete old file every time
			if (PHP_OS == 'Linux'){
				// $cmd = 'chmod -R a+r '.$fileName;
				// $line = exec($cmd, $output, $retVal);	
				// if ($retVal){ // failed
					// // print_r("chmod retVal = $retVal");
					// return;
				// }
				$cmd = 'rm -f '.$fileName;
				$line = exec($cmd, $output, $retVal);	
				if ($retVal){ // failed
					print_r("rm retVal = $retVal");
					return;
				}
			}
		}
		if(!empty($no_cases)){
			$fileName = $this->tool->formatFileName($fileName);
			$handle = fopen($fileName,'w');
			if($handle){
				foreach($no_cases as $k=>$v){
					if($k == 'prjs'){
						// write all into a txt file
						fwrite($handle, "Prjects Does Not Exists:"."\n");
						foreach($v as $prjs)
							fwrite($handle,"  - ".$prjs."\n");
					}
					else if($k == 'cases'){
						// write all into a txt file
						fwrite($handle, "Case Under Project Does Not Exists:"."\n");
						foreach($v as $prj=>$cases){
							fwrite($handle,"  ".$prj.":"."\n");
							foreach($cases as $case)
								fwrite($handle,"    - ".$case."\n");
						}
					}
				}
			}
			fclose($handle); 
			// if (PHP_OS == 'Linux'){
				// $cmd = 'chmod -R a+r '. REPORT_ROOT."/*";
				// $line = exec($cmd, $output, $retVal);	
				// if ($retVal){ // failed
					// // print_r("chmod retVal = $retVal");
					// return;
				// }
			// }
			return $fileName;
		}
	}
}
?>