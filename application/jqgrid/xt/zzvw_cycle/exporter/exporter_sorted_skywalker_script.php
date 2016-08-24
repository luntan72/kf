<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_zzvw_cycle_exporter_sorted_skywalker_script extends exporter_txt{
	protected function _export(){
		$db = dbFactory::get($this->params['db']);
		$sql = "SELECT d_code, command, auto_run_minutes FROM zzvw_cycle_detail".
			" WHERE cycle_id =".$this->params['id']." AND auto_level_id IN (".$this->getAutoLevel().") ORDER BY auto_run_minutes ASC"; //按自动运行时间排序
		$result = $db->query($sql);
		$str = '';
		while ($row = $result->fetch()){
			if(empty($row["command"])) continue;

			$pad = '';
			if ($row['auto_run_minutes'] < 0)
			   $pad = 'U';
			else if (!empty($row['auto_run_minutes'])){
				$hhh = ceil($row['auto_run_minutes'] / 60);
				if ($hhh > 1){
					$pad = str_pad($pad, $hhh - 1, 'H');
				}
			}
			if (!empty($pad))
				$row['d_code'] .= '_'.$pad;
			
			$this->str .= $row['d_code'].' '.$row['command']."\n";
		}
	}
	
	protected function getAutoLevel(){
		return AUTO_LEVEL_AUTO.','.AUTO_LEVEL_MANUAL.','.AUTO_LEVEL_PARTIAL_AUTO;
		return AUTO_LEVEL_AUTO;
	}
};
?>