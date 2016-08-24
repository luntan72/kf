<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_zzvw_cycle_detail_exporter_sorted_skywalker extends exporter_txt{
	protected function _export(){
		$db = dbFactory::get($this->params['db']);
		$sql = "SELECT d_code, command, auto_run_minutes FROM zzvw_cycle_detail".
			" WHERE id in (".implode(',', $this->params['id']).") ORDER BY auto_run_minutes ASC"; //按自动运行时间排序
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
};
?>