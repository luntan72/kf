<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_activate extends action_jqgrid{
	protected function handlePost(){
		$ret = $this->tool->update($this->get('table'), array('isactive'=>ISACTIVE_ACTIVE), 
			"id in (".implode(',', $this->params['id']).") and creater_id = ".$this->userInfo->id, 
			$this->get('db'));
		if(!$ret){
			foreach($this->userInfo->roles as $role){
				if('admin' == $role){
					$this->tool->update($this->get('table'), array('isactive'=>ISACTIVE_ACTIVE), 
						"id in (".implode(',', $this->params['id']).") and creater_id = ".$this->userInfo->id, 
						$this->get('db'));
				}
			}
		}
	}
}

?>