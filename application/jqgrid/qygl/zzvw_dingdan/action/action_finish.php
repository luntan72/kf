<?php
require_once('action_jqgrid.php');
require_once('const_def_qygl.php');

class qygl_zzvw_dingdan_action_finish extends action_jqgrid{
	protected function handlePost(){
		$ids = implode(',', $this->params['id']);
		$this->tool->update('dingdan', array('dingdan_status_id'=>DINGDAN_STATUS_JIESHU), "id in ($ids)", $this->get('db'));
	}
}

?>