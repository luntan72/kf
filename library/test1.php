<?php
require_once('kf_db.php');

$xt_db = new kf_db(array('db'=>'xt'));
$user_db = new kf_db(array('db'=>'useradmin'));

$res = $xt_db->query("SELECT * FROM auto_level");
print_r($res->fetchAll());

$res = $user_db->query("SELECT * FROM users");
print_r($res->fetchAll());

?>