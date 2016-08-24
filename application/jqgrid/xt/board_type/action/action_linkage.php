<?php
require_once('action_jqgrid.php');

class xt_board_type_action_linkage extends action_jqgrid{
	protected function handlePost(){
		$sql = "SELECT distinct board_type.id, board_type.name FROM board_type";
		$where = "1";
		if(!empty($this->params['value'])){
			$res = $this->tool->query("SELECT board_type_ids FROM chip_type WHERE id={$this->params['value']}");
			$row = $res->fetch();
			$where = " id in ({$row['board_type_ids']})";
		}
		$res = $this->tool->query($sql." WHERE ".$where." ORDER BY name");
		$rows = $res->fetchAll();
		return json_encode($rows);
	}
}

?>