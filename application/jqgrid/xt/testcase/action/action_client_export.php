<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_export.php');

class xt_testcase_action_client_export extends action_export{ // 客户端脚本调用，不检查权限
	// protected function getViewParams($params){
		// $view_params = parent::getViewParams($params);
		// $view_params['view_file_dir'] = '/jqgrid/xt/testcase/view';
		// $prj = array();
		// $res = $this->tool->query("SELECT * FROM prj WHERE 1");
		// while($row = $res->fetch()){
			// $prj[$row['id']] = $row['name'];
		// }
		// $view_params['prj'] = $prj;
		// return $view_params;
	// }
	
	// protected function handlePost(){
		// print_r($this->params);
		// return parent::handlePost();
		
		// $exporter = exporterFactory::get($this->params['export_type'], $this->params);
		// $exporter->setOptions($this);
		// return $exporter->export();
	// }
	
}

?>