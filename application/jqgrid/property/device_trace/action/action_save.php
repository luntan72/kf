<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');

class property_device_trace_action_save extends action_save{
	protected function afterSave($affectID){
		parent::afterSave($affectID);
		$this->tool->update('device', array('owner_id'=>$this->params['borrower_id']), "id=".$this->params['device_id']);
	}
}

?>