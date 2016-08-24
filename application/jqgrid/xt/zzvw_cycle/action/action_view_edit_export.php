<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_export.php');
require_once('exporterfactory.php');

class xt_zzvw_cycle_action_view_edit_export extends action_export{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
		$view_params['view_file'] = 'view_edit_export_type.phtml';
		$view_params['view_file_dir'] = '/jqgrid/xt/zzvw_cycle/view';
		return $view_params;
	}
}

?>