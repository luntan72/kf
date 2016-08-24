<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');

class qygl_zzvw_unit_action_save extends action_save{
	protected function newRecord($db, $table, $pair){
		//如果该单位类型下只有这一个单位，则默认设置成标准单位。
		$this->tool->setDb($db);
		$res = $this->tool->query("SELECT * FROM unit_fl WHERE id={$pair['unit_fl_id']}");
		$row = $res->fetch();
		if($row['unit_id'] == 0){
			$pair['fen_zi'] = $pair['fen_mu'] = 1;
		}
		$affectedID = parent::newRecord($db, $table, $pair);
		if($row['unit_id'] == 0){
			$this->tool->update('unit_fl', array('unit_id'=>$affectedID), "id=".$pair['unit_fl_id']);
		}
		return $affectedID;
	}
}
?>