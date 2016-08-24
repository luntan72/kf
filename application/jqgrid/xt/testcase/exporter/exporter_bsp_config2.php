<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_testcase_exporter_bsp_config2 extends exporter_txt{ // used by client export
	protected function _export(){
// print_r($this->params);
		$db = dbFactory::get($this->params['db']);
		$sql = "SELECT prj.id as prj_id, chip.name as chip, os.name as os, board_type.name as board_type".
			" FROM prj left join chip on prj.chip_id=chip.id".
			" left join os on prj.os_id=os.id".
			" left join board_type on prj.board_type_id=board_type.id".
			" WHERE prj.name=:name";
		$res = $db->query($sql, array('name'=>$this->params['prj']));
		$row = $res->fetch();
		$this->str .= "chip={$row['chip']}, os={$row['os']}, board_type={$row['board_type']}\n";
		
		$sql = "SELECT tc.code, ver.config ".
			" FROM testcase_ver ver left join testcase tc on tc.id=ver.testcase_id ".
			" left join prj_testcase_ver link on link.testcase_ver_id=ver.id".
			" WHERE tc.testcase_type_id=1 and tc.isactive=".ISACTIVE_ACTIVE." and link.prj_id={$row['prj_id']}".
			" AND ver.testcase_priority_id in ({$this->params['priority']})".
			" AND ver.auto_level_id in ({$this->params['auto_level']})".
			" and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")";
		$res = $db->query($sql);
		while($row = $res->fetch()){
			$this->str .= $row['code'].' '.$row['config']."\n";
		}
	}

	public function export(){
		$this->_export();
		return $this->str;
	}
};

?>
