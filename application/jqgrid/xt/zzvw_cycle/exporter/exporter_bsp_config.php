<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_zzvw_cycle_exporter_bsp_config extends exporter_txt{
	protected function _export(){
// print_r($this->params)	;
		// $ids = implode(',', $this->params['id']);
		$db = dbFactory::get($this->params['db']);
		$sql = "SELECT chip.name as chip, os.name as os, board_type.name as board_type".
			" FROM cycle_prj left join prj on cycle_prj.prj_id=prj.id".
			" left join chip on prj.chip_id=chip.id".
			" left join os on prj.os_id=os.id".
			" left join board_type on prj.board_type_id=board_type.id".
			" WHERE cycle_prj.cycle_id={$this->params['id']}";//默认只有一个prj
		$res = $db->query($sql);
		$row = $res->fetch();
		$this->str .= "chip={$row['chip']}, os={$row['os']}, board_type={$row['board_type']}\n";
		
		$sql = "SELECT tc.code, ver.config ".
			" FROM cycle_detail left join testcase_ver ver on cycle_detail.testcase_ver_id=ver.id".
			" left join testcase tc on tc.id=ver.testcase_id ".
			" WHERE cycle_detail.cycle_id={$this->params['id']}";
		$res = $db->query($sql);
		while($row = $res->fetch()){
			$this->str .= $row['code'].' '.$row['config']."\n";
		}
	}
};

?>
