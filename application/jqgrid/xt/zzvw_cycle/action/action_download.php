<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_download extends action_jqgrid{
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Download Type';
		$view_params['view_file'] = 'download_type.phtml';
		$view_params['view_file_dir'] = '/jqgrid/xt/'.$this->get('table')."/view" ;
		$view_params['blank'] = 'false';
		return $view_params;
	}
}

?>