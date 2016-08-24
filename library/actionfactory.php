<?php
class actionFactory{
	static function get($controller, $action = '', $params = array()){
		static $actions = array();
		if(empty($controller)){
			$controller = Zend_Controller_Front::getInstance();
		}
		else{
			$params = $controller->getRequest()->getParams();
		}
// print_r($params);		
		if(!empty($action))
			$params['oper'] = $action;
		$action = 'action_'.(isset($params['oper']) ? $params['oper'] : $params['action']);
// print_r("action = $action\n");			
		if ($action == 'action_edit')$action = 'action_save';
		$db = $params['db'];
		$table = $params['table'];
		$index = $db.'_'.$table.'_'.$action;
		if (!isset($actions[$index])){
			$found = false;
			$dirs = array(
				"jqgrid/$db/$table/action"=>$db.'_'.$table.'_'.$action,
				"jqgrid/$db/action"=>$db.'_'.$action, 
				"jqgrid/action"=>$action);
			foreach($dirs as $dir=>$className){
				$classFile = realpath(APPLICATION_PATH."/$dir/$action.php");
				if(file_exists($classFile)){
					$found = true;
					break;
				}
			}
			if(!$found){
				$classFile = 'action_base.php';
				$className = 'action_base';
			}
// print_r("classfile = $classFile\n");			
			require_once($classFile);
			$actions[$index] = new $className(array('controller'=>$controller));
			$actions[$index]->post_init();
// print_r("after post_init");			
		}
		$actions[$index]->setParams($params);
// print_r("after setParms");		
		return $actions[$index];
	}
}

?>