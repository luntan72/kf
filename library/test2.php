<?php
require_once('skywalker_logfile_parse.php');
require_once('xt_common.php');

function transBoardType($boardType){
	$ret = $boardType;
	$board_type_trans = array('SABREAUTO'=>'ARD', 'SABRE_SD'=>'SABRE_SDB', 'SABRE'=>'SABRE_SDB');
	if(!empty($board_type_trans[$boardType]))
		$ret = $board_type_trans[$boardType];
	return $ret;
}

function transChip($chip){
	$ret = $chip;
	if(substr($chip, 0, 3) == 'IMX')
		$ret = "i.MX".substr($chip, 3);
	return $ret;
}

function parseFile($parser, $file, $xt_common){
print_r("start to parse file:$file\n");	
	$parser->setFile($file);
	$parser->parse();
	$data = $parser->getData();
	unset($parser);
	
// print_r($data['cycle']);
// return;
	if(!isset($data['cycle']['rel']))
		return;
	
	if(empty($data['cycle_detail']) || count($data['cycle_detail']) < 100)
		return;
	
	$data['cycle']['logfile'] = $file;
	$data['cycle']['cycle_status_id'] = 2;
	$data['cycle']['board_type'] = transBoardType($data['cycle']['board_type']);
	$data['cycle']['chip'] = transChip($data['cycle']['chip']);
	$data['cycle']['os'] = 'Linux_4.1';
	$data['cycle']['creater_id'] = 59;//Andy Tian
// print_r($data);
// return;
	$data['cycle']['group_id'] = 1;//'LinuxBSP';
	$data['cycle']['type'] = 'Auto';
	$data['cycle']['myName'] = 'Auto_Created_AT_'.date('His');
	$data['cycle']['test_env_id'] = 1;//'default_env';
	$data['cycle']['testcase_type'] = 'LinuxBSP';
	$prj = $xt_common->createPrj(array('board_type'=>$data['cycle']['board_type'], 'chip'=>$data['cycle']['chip'], 'os'=>$data['cycle']['os']));
	$data['cycle']['prj'] = $prj['name'];
	$data['cycle']['prj_ids'] = $prj['id'];
	//处理Release信息
	$rel = array('name'=>$data['cycle']['rel'], 'rel_category_id'=>2); //Daily release
	$data['cycle']['rel_id'] = $xt_common->createRelease($rel, $prj['os_id']);
	$data['cycle']['id'] = $xt_common->createCycle($data['cycle']);
print_r($data['cycle']);
	$xt_common->insertCycleDetail($data['cycle'], $data['cycle_detail']);
}

date_default_timezone_set('Asia/Shanghai'); 
$params['keyword'] = array(
	'start'=>'/<<<test_start>>>/', 
	'case_and_starttime'=>'/tag=(.*) stime=(\d*)/',
	'release'=>'/Linux version ([^\s]*)/',
	'case_result'=>'/duration=(\d*) termination_type=(.*?) termination_id=(\d*)/',
	'stop'=>'/<<<test_end>>>/', 
);
$context = new context($params);
$parser = new skywalker_logfile_parse($params);
$parser->setContext($context);
$xt_common = new xt_common(array(
	// 'root'=>"C:\\Users\\b19268\\xampp\\kf", 
	'root'=>"\\\\10.192.225.199\\xt\\xt_3.0.2", 
	'db'=>array(
		'dbname'=>'xt', 
		'host'=>'10.192.225.199', 
		'username'=>'root', 
		'password'=>'dbadmin'
		)
	));
$xt_common->test();
return;

// return;
// for($i = 6480; $i < 7000; $i ++)
	// $xt_common->delCycle($i);
// $xt_common->delRel(2257);

// $file = "\\\\10.192.244.37\\rootfs\\vte_IMX6DL-SABREAUTO\\output\\IMX6DL-SABREAUTO_cmd_472_log_00_04_9f_03_e6_74_1d1e24";
// parseFile($parser, $file, $xt_common);
// $file = "\\\\10.192.244.37\\rootfs\\vte_IMX6DL-SABREAUTO\\output\\IMX6DL-SABREAUTO_cmd_446_log_00_04_9f_03_e6_74_ae121b";
// parseFile($parser, $file, $xt_common);
// $file = "\\\\10.192.244.37\\rootfs\\vte_IMX6DL-SABREAUTO\\output\\IMX6DL-SABREAUTO_cmd_474_log_00_04_9f_03_e6_74_79e9f8";
// parseFile($parser, $file, $xt_common);

// return;

$dir = "\\\\10.192.244.37\\rootfs";
$dh = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if(is_dir($dir."\\".$filename) && substr($filename, 0, 7) == 'vte_IMX' && substr($filename, -1, 2) != '_d'){
		$subdir = $dir."\\".$filename."\\output";
		if(file_exists($subdir) && is_dir($subdir)){
			$files = scandir($subdir);
			foreach($files as $file){
				$last = substr($file, -7);
				if($last == '.failed' || $last == '_conlog')
					continue;
				if(substr($file, 0, 3) != 'IMX')
					continue;
				$realfile = $subdir."\\".$file;
				if(filesize($realfile) < 1 * 1024 * 1024)
					continue;
				if(filesize($realfile) > 10 * 1024 * 1024)
					continue;
				
				parseFile($parser, $realfile, $xt_common);
			}
		}
	}
}
closedir($dh);
?>