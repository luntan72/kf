<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_export.php');

class xt_rel_action_export extends action_export{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
		$view_params['view_file_dir'] = '/jqgrid/xt/rel/view';
		return $view_params;
	}
}

?>