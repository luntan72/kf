<?php
require_once('context.php');
require_once('state.php');

class stateFactory{
	static function get($context, $stateName){
		static $states = array();
		if(empty($states[$stateName])){
			$className = 'state_'.$stateName;
			$fileName = $className.'.php';
			if(file_exists($fileName)){
				require_once($fileName);
			}
			else{
				$className = 'state';
			}
			$states[$stateName] = new $className($context);
		}
		return $states[$stateName];
	}
}
?>