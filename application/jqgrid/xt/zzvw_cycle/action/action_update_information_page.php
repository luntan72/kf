<?php 
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/action/action_information.php');

class xt_zzvw_cycle_action_update_information_page extends xt_zzvw_cycle_action_information{
	protected function getViewParams($params){
		$view_params = $this->getDefaultParamsForView($params);
//print_r($view_params);		
		$page = $this->params['page'];
        $methods = get_class_methods($this);
		$method = 'paramsFor_'.$page;
		if (in_array($method, $methods)){
			$view_params = $this->$method($view_params);
			if($page == "cycle_overnight"){
				$view_params['view_file_dir'] = "";
				$view_params['view_file'] = "cycle_overnight.phtml";
			}
		}		
		return $view_params;
	}
}
?>