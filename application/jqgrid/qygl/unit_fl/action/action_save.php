<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');

class qygl_unit_fl_action_save extends action_save{
	protected function updateRecord($db, $table, $pair, $id = 'id'){
// print_r($db);
// print_r($pair);
// print_r($table);
		//要先获取原有的unit_id
		$this->tool->setDb($db);
		$res = $this->tool->query("SELECT * FROM unit_fl WHERE id={$pair['id']}");
		$orig = $res->fetch();
// print_r($orig);		

		$affectedID = parent::updateRecord($db, $table, $pair, $id);
// return $pair['id'];
		
		if($orig['unit_id'] != $pair['unit_id']){ //改变了标准单位，则其他单位要跟着调整比例关系
			$res = $this->tool->query("select * from unit where id={$pair['unit_id']}");
			$row = $res->fetch();
			
			$this->tool->update('unit', array('fen_zi'=>$row['fen_mu'], 'fen_mu'=>$row['fen_zi']), "id=".$orig['unit_id']);
			$this->tool->update('unit', array('fen_zi'=>1, 'fen_mu'=>1), "id=".$pair['unit_id']);
			$this->tool->query("update unit set fen_zi=fen_zi*{$row['fen_mu']}, fen_mu=fen_mu*{$row['fen_zi']} WHERE unit_fl_id={$pair['id']} AND id!={$row['id']} AND id!={$orig['unit_id']}");
		}
		return $affectedID;
	}
}
?>