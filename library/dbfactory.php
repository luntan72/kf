<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
require_once('Zend/Db.php');
require_once('Zend/Controller/Front.php');
require_once("useradminfactory.php");
// require_once(APPLICATION_PATH.'/models/Useradmin.php');

class dbFactory{
	static function get($db, & $realDbName = ''){
		static $dbs = array();
		static $db_map = array();
		if(!is_array($db)){
			$db = array('dbname'=>$db);
		}
		$db_name = $db['dbname'];
// print_r("++++db anme = $db_name+++++\n");	
// print_r($dbs);	
// if($db_name == 'xt_nn')
	// debug_print_backtrace();
		if (!isset($dbs[$db_name])){
			$dsn = $db;
			$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
			$multiDb = array();
			if($bootstrap){
				$multiDb = $bootstrap->getResource('multidb')->getOptions();
			}
			if (isset($multiDb[$db_name]))
				$dsn = array_merge($dsn, $multiDb[$db_name]);
			if (empty($dsn['dbname'])) $dsn['dbname'] = $db_name;
			if (!isset($dsn['host']))$dsn['host'] = 'localhost';
			if (!isset($dsn['username']))$dsn['username'] = 'root';
			if (!isset($dsn['password']))$dsn['password'] = 'dbadmin';
// print_r($multiDb);			
// $dsn['driver_options'][Zend_Db::ATTR_CURSOR] = Zend_Db::CURSOR_SCROLL;
			if(0){//$db_name != 'useradmin'){
				$userAdmin = useradminFactory::get();//new Application_Model_Useradmin(null);
				$userInfo = $userAdmin->getUserInfo();
				$user_id = $userInfo->id;
				if($user_id > 0){
					$user_config = $userAdmin->getConfigInfo($db_name, $user_id);
					if(!empty($user_config)){
						$user_config = json_decode($user_config, true);
// print_r($user_config)						;
						$dsn = array_merge($dsn, $user_config);
						
					}
				}
			}
			$db_map[$db_name] = $dsn['dbname'];
// print_r("dsn = ");			
// print_r($dsn);			
			try{
				$db = Zend_Db::factory('PDO_MYSQL', $dsn);
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$db->query("set names 'utf8'");
				$dbs[$db_name] = $db;
			}catch(Exception $e){
				print_r($e->getMessage());
				$dbs[$db_name] = null;
			}
// print_r(">>>>>>>>>>>>new db, db_name = $db_name<<<<<<<<<<<<<<<<<<\n");			
		}
		$realDbName = $db_map[$db_name];
		return $dbs[$db_name];
	}
}

?>