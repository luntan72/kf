<?php
require_once(APPLICATION_PATH.'/models/useradmin.php');
class useradminFactory{
	static function get(){
		static $useradmin = null;
		if(empty($useradmin))
			$useradmin = new Application_Model_Useradmin(null);
		return $useradmin;
	}
}
?>