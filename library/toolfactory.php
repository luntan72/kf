<?php
class toolFactory{
	static function get($params = array()){
// print_r($params);	
		static $tool = null;
		if(is_null($tool)){
			$classDir = realpath(APPLICATION_PATH."/../library");
			$classFile = $classDir.'/kf_tool.php';
			$className = 'kf_tool';
			require_once($classFile);
			$tool = new $className();
		}
		if(!empty($params['db']))
			$tool->setDb($params['db']);
		return $tool;
	}
}
?>