<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');

class qygl_unit_fl_action_get_standard_unit extends action_save{
	protected function handlePost(){
		$res = $this->tool->query("SELECT unit.id, unit.name FROM unit LEFT JOIN unit_fl on unit_fl.unit_id=unit.id WHERE unit_fl.id={$this->params['id']}");
		if($row = $res->fetch())
			$row['cc'] = 1;
		else
			$row['cc'] = 0;
		return json_encode($row);
	}
}
?>