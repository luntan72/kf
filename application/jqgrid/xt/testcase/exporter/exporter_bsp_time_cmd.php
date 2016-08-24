<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_testcase_exporter_bsp_time_cmd extends exporter_txt{
	protected function _export(){
		$db = dbFactory::get($this->params['db']);
		$ids = implode(',', $this->params['id']);
		$sql = "SELECT tc.code, ver.command, ver.auto_run_minutes ".
			" FROM testcase_ver ver left join testcase tc on tc.id=ver.testcase_id ".
			" left join prj_testcase_ver link on link.testcase_ver_id=ver.id".
			" WHERE tc.id in ($ids) and link.prj_id={$this->params['prj_id']}".
			" and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")".
			" ORDER BY ver.auto_run_minutes ASC";
		$res = $db->query($sql);
		while($row = $res->fetch()){
			$this->str .= $row['code']."\t".$row['auto_run_minutes']."\t".$row['command']."\n";
		}
	}
};

?>
