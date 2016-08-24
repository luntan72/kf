<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_zzvw_cycle_detail_exporter_bsp_time_cmd extends exporter_txt{
	protected function _export(){
// print_r("xxxxxxxxx");
		$db = dbFactory::get($this->params['db']);
		$ids = implode(',', $this->params['id']);
		$sql = "SELECT tc.code, ver.command, ver.auto_run_minutes ".
			" FROM cycle_detail detail LEFT JOIN testcase_ver ver ON ver.id = detail.testcase_ver_id".
			" LEFT JOIN testcase tc ON tc.id=ver.testcase_id ".
			" LEFT JOIN prj_testcase_ver link ON link.testcase_ver_id=ver.id AND link.prj_id=detail.prj_id".
			" WHERE detail.id in ($ids) AND tc.isactive=".ISACTIVE_ACTIVE.
			" AND ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")".
			" ORDER BY ver.auto_run_minutes ASC";
		$res = $db->query($sql);
		while($row = $res->fetch()){
			$this->str .= $row['code']."\t".$row['auto_run_minutes']."\t".$row['command']."\n";
		}
	}
};

?>
