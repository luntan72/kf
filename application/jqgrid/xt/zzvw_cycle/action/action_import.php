<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_import.php');

class xt_zzvw_cycle_action_import extends action_import{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
// print_r($view_params);
		$view_params['view_file_dir'] = '/jqgrid/xt/zzvw_cycle/view';
		$view_params['testcase_type_ids']['value'] = array('0'=>'', TESTCASE_TYPE_MQX=>'MQX', TESTCASE_TYPE_CODEC=>'CODEC',
			TESTCASE_TYPE_KSDK_KSV=>'KSDK-KSV', TESTCASE_TYPE_KSDK_DEMO=>'KSDK-Demo', TESTCASE_TYPE_LINUX_BSP=>'Linux Bsp');
		$config_types = array(
			'0'=>'',
			'N1'=>"New: Create Cycle For Linux(excel)",
			'U6'=>"Update: Update full log_link and update log_link, dp_detailid, logFile to logs",
			// 'N2' => "New: Create Cycle For KSDK",
            // 'N3' => "New: Create Cycles For MQX",
			// 'U1' => "New: Create: For LinkTables",
			// 'U2' => "Update: Testcase Last Result",
			// 'U3' => "Update: MQX Log RC1",		
			// 'U4' => "Update: MQX Log RC2",
			// 'U5' => "Update: No Function For spring",
			// 'O10' => "Find: Cycle No Function For Linux(excel)",
			// 'O11' => "Test: Access 49",
		);
		$res = $this->tool->query("select id, name from test_env");
		$data[0] = '';
		while($info = $res->fetch()){
			$data[$info['id']] = $info['name'];
		}
		$view_params['test_env_id']['value'] = $data;
		unset($data);
		$res = $this->tool->query("select id, name from prj");
		$data[0] = '';
		while($info = $res->fetch()){
			$data[$info['id']] = $info['name'];
		}
		$view_params['prj_ids']['value'] = $data;
		unset($data);
		// $res = $this->tool->query("select id, name from cycle_type");
		// $data[0] = '';
		// while($info = $res->fetch()){
			// $data[$info['id']] = $info['name'];
		// }
		// $view_params['cycle_type_id']['value'] = $data;
		// unset($data);
		//  get the owner list
		$owner = array(0=>'', 48=>'Admin FSL', 65=>'Jian Zhang', '81'=>'Bill Yuan');
		$view_params['owner']['value'] = $owner;
		$useradmin = dbfactory::get('useradmin');
		$res = $useradmin->query("select id, name from groups order by id asc");
		$group[0] = '';
		while($info = $res->fetch()){
			$group[$info['id']] = $info['name'];
		}
		$view_params['group']['value'] = $group;
		
		if(!empty($params['id'])){
			$view_params['id'] = $params['id'];
			//$view_params['testcase_type']['value'] = array('0'=>'', '1'=>'LinuxBSP', '2'=>'CODEC');
			$config_types = array(
				'0'=>'',
				'1'=>'Update Results: GVB (log)',
				'2'=>'Update Results: Apollo (xml)',
				'3'=>'Update Results: CTE (excel)',
				'4'=>'Update Results: SkyWalker (txt)',
				'5'=>'!!!Update Results: SkyWalker( Linux 3.0.5 ) !!! (txt)',
				'6'=>'Update Results: Linux_Codec_Case (txt)',
				'7'=>'Update Results: USB-FPT (excel)',
			);

			$res = $this->tool->query("select group_id, testcase_type_ids, creater_id, test_env_id, prj_ids, compiler_ids, build_target_ids, tester_ids from cycle where id = ".$params['id']);
			if($info = $res->fetch()){
				$view_params['defval']['test_env_id'] = $info['test_env_id'];
				$view_params['group_id'] = $info['group_id'];
				$this->processData($view_params, 'testcase_type', $info['testcase_type_ids']);
				$this->processData($view_params, 'prj', $info['prj_ids']);
				$this->processData($view_params, 'compiler', $info['compiler_ids']);
				$this->processData($view_params, 'build_target', $info['build_target_ids']);
				$owner = $this->userAdmin->getUserList(array('id'=>$info['tester_ids']));
				$view_params['owner']['value'] = $owner;
			}
			$res = $this->tool->query("select distinct test_env.name as name, test_env.id as id from cycle_detail".
				" left join test_env on test_env.id = cycle_detail.test_env_id where cycle_id = ".$params['id']);
			$env = array();
			$env[0] = "";
			while($info = $res->fetch()){
				$env[$info['id']] = $info['name'];
			}
			$view_params['test_env_id']['value'] = $env;
		}
		$view_params['defval']['owner_id']= $this->userInfo->id;
		//$view_params['defval']['cycle_type_id'] = '1';
		$view_params['config_type'] = $config_types;
		return $view_params;
	}
	
	private function processData(&$params, $table, $value){
		if(empty($value))
			return;
		$key = $table."_ids";
		$cart_data = new stdClass;	
		$cart_data->filters = '{"groupOp":"AND","rules":[{"field":"id","op":"in","data":"'.$value.'"}]}';
		$params['cart_data'][$key] = json_encode($cart_data);
		$result = $this->tool->query("select id, name from {$table} where id in (".$value.")");
		$data[0] = '';
		while($row = $result->fetch())
			$data[$row['id']] = $row['name'];
		$params[$key]['value'] = $data;
		if(stripos($value, ",") === false)
			$params['defval'][$key] = $value;
	}
	
	protected function handlePost(){
		//config file list
		$config_files = array(
			'N1'=>'config.linux.cycle.php', //new for linux
			// 'N2'=>'config.ksdk.report.php', //update for ksdk
			// 'N3'=>'config.mqx.cindy.php', //find function for linux
			// 'U1'=>'', 
			// 'U2'=>'', 
			// 'U3'=>'',
			// 'U4'=>'', 
			// 'U5'=>'',
			'U6'=>'',
			// 'O1'=>'',
			// 'O2'=>'config.linux.function.php',
			
			'1'=>'',
			'2'=>'',
			'3'=>'config.codec.cte.php',
			'4'=>'',
			'5'=>'',
			'6'=>'',
			'7'=>'config.usb.report.php',
		);		
		$this->params['config_file'] = $config_files[$this->params['config_type']];			
		if (!empty($this->params['config_file']))
			$this->params['config_file'] = APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/config/'.$this->params['config_file'];
		
		if(!empty($_FILES['uploaded_file']['name']))
			$fileName = $_FILES['uploaded_file']['name'];		
		$pathInfo = pathinfo($fileName);
		$extension = $pathInfo['extension'];
		
		switch($this->params['config_type']){
			case 1: //cycle: gvb log
				if($extension != 'log')
					return "You Upload a Wrong File. Pls check your log and make sure its extension is log!";
				$class = 'update_codec_gvb';
				break;
			case 2: //cycle: apollo xml
				if($extension != 'xml')
					return "You Upload a Wrong File. Pls check your file and make sure its extension is xml!";
				$class = 'update_codec_apollo';
				break;
			case 3: //cycle: cte excel
				if($extension != 'xlsx' && $extension != 'xls')
					return "You Upload a Wrong File. Pls check your file and make sure its extension is xlsx or xls!";
				$class = 'update_codec_cte';
				break;
			case 4: //cycle: skywalker txt
				if($extension != 'txt')
					return "You Upload a Wrong File. Pls check your file and make sure its extension is txt!";
				$class = 'update_linux_skywalker';
				break;	
			case 5: //cycle: 3.0.5 skywalker txt
				if($extension != 'txt')
					return "You Upload a Wrong File. Pls check your file and make sure its extension is txt!";
				$class = 'update_linux_skywalker305';
				break;
			case 6: //cycle: linux codec
				$class = 'update_codec_new';
				break;
			case 7: //usb ftp
				$class = 'update_usb_result';
				break;
						
			case 'N1':
				$class = 'generate_linux_cycle';
				break;
			// case 'N2':
				// $this->params['sheetsNeedParse'] = array('Cover', 'KSDK_Test_Application', 'KSDK_Test_RTOS');
				// $class = 'generate_ksdk_cycle';
				// break;
			// case 'N3'://what is config excel
				// $class = 'generate_mqx_cycle';
				// break;
			// case 'U1':
				// $class = 'update_mqx_log';
				// break;
			// case 'U2':
				// $class = 'update_mqx_log_rc2';
				// break;
				// break;
			// case 'U3':
				// $class = 'update_link_tables';
				// break;
			// case 'U4':
				// $class = 'update_testcase_last_result';
				// break;
			// case 'U5':
				// $class = 'update_cycle_function';
				// break;
			case 'U6':
				$class = 'update_detail_log';
				break;
			// case 'O1':
				// $class = 'access_49';
				// break;
			// case 'O2':
				// $class = 'process_linux_function';
				// break;
			default:
				print_r("No This Type Now!");
				break;
		}
		if(!empty($class)){
			$this->params["tool_name"] = "common";
			$importer = importerFactory::get($class, $this->params);
			$importer->setOptions($this);
			return $importer->import();
		}
		print_r("This Feaure Does Not Finish");
	}
	
	
}

?>