<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_export.php');
require_once('exporterfactory.php');

class xt_zzvw_cycle_action_export extends action_export{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
		$view_params['view_file_dir'] = '/jqgrid/xt/zzvw_cycle/view';
		$res = $this->tool->query("select id, name from testcase_type");
		$view_params['testcase_type'][0] = '';
		while($row = $res->fetch()){
			$view_params['testcase_type'][$row['id']] = $row['name'];
		}
		return $view_params;
	}
}

?>