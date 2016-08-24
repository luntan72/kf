<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_testcase_exporter_sorted_bsp_cmd_hl extends exporter_txt{
	protected function _export(){
// print_r($this->params);		
		$db = dbFactory::get($this->params['db']);
		$ids = implode(',', $this->params['id']);
		$sql = "SELECT tc.code, ver.command, ver.auto_run_minutes ".
			" FROM testcase_ver ver left join testcase tc on tc.id=ver.testcase_id ".
			" left join prj_testcase_ver link on link.testcase_ver_id=ver.id".
			" WHERE tc.id in ($ids) and link.prj_id={$this->params['prj_id']}";
		if(isset($this->params['priority']))
			$sql .= " AND ver.testcase_priority_id in ({$this->params['priority']})";
		if(isset($this->params['auto_level']))
			$sql .= " AND ver.auto_level_id in ({$this->params['auto_level']})";
		$sql .= " and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")";
		$sql .= " ORDER BY ver.auto_run_minutes ASC";
// print_r($sql);
		$res = $db->query($sql);
		while($row = $res->fetch()){
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
				$row['code'] .= '_'.$pad;
			
			$this->str .= $row['code'].' '.$row['command']."\n";
		}
	}
};

?>
