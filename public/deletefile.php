<?php
require_once("../library/dbfactory.php");

$fileName = $_REQUEST['filename'];
$cell_id = $_REQUEST['cell_id'];
$id = $_REQUEST['id'];
$isFiles = $_REQUEST['isfiles'];
$db = $_REQUEST['db'];
$table = $_REQUEST['table'];
//$fileName = realpath($fileName);
$fileName = urldecode(trim($fileName));
if (isset($fileName)){
	// print_r($fileName);
	// print_r("isfile = $isFiles");
	if($isFiles){ //仅仅修改数据库数据，断开连接即可，不实际删除文件
		$o_db = dbFactory::get($db, $rel_db);
		$res = $o_db->query("SELECT $cell_id from $table where id=$id");
		$row = $res->fetch();
		$files = explode(',', $row[$cell_id]);
		$key = array_search($fileName, $fiels);
		if($key !== false){
			unset($key);
			$o_db->update($table, array($cell_id=>implode(',', $files)), "id=$id");
		}
		$ret = 1;
	}
	else
		$ret = unlink($fileName);
	print_r($ret);
}

?>
