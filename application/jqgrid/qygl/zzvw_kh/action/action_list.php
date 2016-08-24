<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_list.php');

class qygl_zzvw_kh_action_list extends action_list{
	public function getMoreInfoForRow($row){
		$row = parent::getMoreInfoForRow($row);
		$res = $this->tool->query("SELECT sum(price * amount) as history_dingdan FROM zzvw_dingdan WHERE hb_id={$row['id']}");
		$tmp = $res->fetch();
		$row['history_dingdan'] = round($tmp['history_dingdan'], 0);
		$res = $this->tool->query("SELECT sum(price * amount) as current_dingdan FROM zzvw_dingdan WHERE hb_id={$row['id']} and dingdan_status_id=1");
		$tmp = $res->fetch();
		$row['current_dingdan'] = round($tmp['current_dingdan'], 0);
		return $row;
	}
}

?>