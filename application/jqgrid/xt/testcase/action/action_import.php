<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_import.php');

class xt_testcase_action_import extends action_import{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
		$view_params['view_file_dir'] = '/jqgrid/xt/testcase/view';
		
		//testcase type
		$res = $this->db->query("SELECT * FROM testcase_type");
		$types = array();
		while($row = $res->fetch()){
			$types[$row['id']] = $row['name'];
		}
		$view_params['testcase_type'] = $types;
		
		//config type list
		$config_types = array(
			'N1'=>'New: Android KK ( Karen-E )',
			'N2'=>'New: Android TrickMode (Codec-E )',
			// 'N3'=>'New: FAS ( Amy-E )',
			// 'N4'=>'New: FAS TrickMode ( Amy-E )',
			// 'N5'=>'New: MQX (Cindy-E )',
			'N6'=>'New: Linux ( Chuan-E )',
			'N7'=>'New: KSDK ( KSDK-E )',
			// 'N8'=>'New: Summary & Project( KSDK-E )',
			'U1'=>'Update: CaseInfo Only ( Shan-E )',
			'U2'=>'Update: With New Version( Shan-E )',
			// 'U3'=>'Update: Summary ( Jane-E )',
			// 'U4'=>'Update: Module ( Jane-E )',
			// 'U5'=>'Update: Project ( KSDK-E )',	
			// 'U6'=>'Update: Config ( Uniform-E )',	
			//'U10'=>'Update: Case Name _ to -',	
			'U11'=>'Update: For Jane160526',
		);
		$view_params['config_type'] = $config_types;
		
		//  get the owner list
		$owner = $this->userAdmin->getUserList(array('role_id'=>ROLE_TESTER));
		$view_params['owner'] = $owner;
		return $view_params;
	}
	
	protected function handlePost(){
		//config file list
		$config_files = array(
			'N1'=>'config.android_kk.karen.php', 
			'N2'=>'config.android.codec.php', 
			'N3'=>'config.fas.amy.php',
			'N4'=>'config.fas.amy_1.php', 
			'N5'=>'config.mqx.cindy.php', 
			'N6'=>'config.linux.chuan.php', 
			'N7'=>'config.psdk.bill.php',
			'N7'=>'config.linux.chuan.php',
			'N8'=>'config.linux.chuan.php',
			
			'U1'=>'config.codec.shan_update.php', 
			'U2'=>'config.codec.shan_update_new.php', 
			'U3'=>'config.linux.jane.php',
			'U4'=>'config.linux.jane_new.php',
			'U5'=>'config.new.version.php',
			'U6'=>'config.linux.chuan.php',			
			'U7'=>'shan_cmd_xml',
			'U8'=>'del_psdk_cases',
			'U10'=>'',
			'U11'=>'config.linux.chuan.php',
		);
		$this->params['config_file'] = $config_files[$this->params['config_type']];	
		
		if (!empty($this->params['config_file']))
			$this->params['config_file'] = APPLICATION_PATH.'/jqgrid/xt/testcase/config/'.$this->params['config_file'];
		
		switch($this->params['config_type']){
			case 'N1':
			case 'N2':
				$class = 'testcase';
				break;
			case 'N3': 	//'config.fas.amy.php':
			case 'N4': 	//'config.fas.amy_1.php':
				$class = 'testcase_fas';
				break;
			case 'N5': 	//'config.mqx.cindy.php':
				$class = 'testcase_mqx';
				break;
			case 'N6': 	//'config.linux.chuan.php':
				$class = 'testcase_linux';
				break;
			case 'N7': 	//'config.psdk.bill.php':
			case 'N8': 	//'config.linux.chuan.php':
				$class = 'testcase_psdk';
				break;
			case 'U2':  //config.codec.shan_update_new.php':
				$class = 'new_codec';
				break;
			case 'U1':  //'config.codec.shan_update.php':
			case 'U3': 	//'config.linux.jane.php':
			case 'U4': 	//'config.linux.jane_new.php':
			case 'U6': 	//'config.linux.chuan.php':
				$class = 'update';
				break;
			case 'U5': 	//'config.new.version.php':
				$class = 'new_version';
				break;
			case 'U7': 	//'shan_cmd_xml':
				$class = 'cmd_codec';
				break;
			case 'U8':
				$class = 'delete_testcase_psdk';
				break;
			case 'U10':
				$class = 'update_bsp';
				break;
			case 'U11':
				$class = 'update_for_jane';
				break;
			default:
				$class = '';
				print_r("Sorry, this feature does NOT exist!");
				break;
		}
		if(!empty($class)){
			$this->params["tool_name"] = "common";
			$importer = importerFactory::get($class, $this->params);
			$importer->setOptions($this);
			return $importer->import();
		}
	}
	
	
}

?>