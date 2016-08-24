<?php
class tableDescFactory{
	static function get($db, $table, $params = array(), $action = null){
		static $desc = array();
		$params['self_action'] = $action;
		if(empty($params['action_name'])){
			if(!empty($action))
				$params['action_name'] = $action->getActionName();
			else
				$params['action_name'] = 'none';
		}
		$className = $db.'_'.$table;
		$index = $className.'_'.$params['action_name'];
// print_r($index);		
		if (empty($desc[$index])){
			$classFile = realpath(APPLICATION_PATH."/jqgrid/$db/$table/$table.php");
			if (!file_exists($classFile)){
				$className = 'table_desc';
				$classFile = 'table_desc.php';
			}
			require_once($classFile);
			$params['db'] = $db;
			$params['table'] = $table;
// print_r($className);
			$o = new $className($params); 
			$o->post_init();
			$desc[$index] = $o;
		}
		return $desc[$index];
	}
}

?>