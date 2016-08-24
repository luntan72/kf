<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_action_update_dp extends action_jqgrid{
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Update DaPeng';
		$view_params['view_file'] = 'newElement.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
		$data = array();
		$mydb = dbFactory::get('mydb');
		$result = $mydb->query("SELECT id, name FROM zzvw_mcuauto_request");
		$data[0] = '';
		while($row = $result->fetch())
			$data[$row['id']] = $row['name'];
		$res = $this->tool->query('select zzvw_mcuauto_request_ids from cycle where id = '.$params['id']);
		$row = $res->fetch();
		$view_params['cols'] = array(
			//array('id'=>'zzvw_mcuauto_request_ids', 'name'=>'zzvw_mcuauto_request_ids', 'label'=>'DaPeng Request Ids', 'rows'=>5, 'editable'=>true, 'DATA_TYPE'=>'text', 'type'=>'textarea', 'defval'=>$row['zzvw_mcuauto_request_ids'])
			array('id'=>'update_zzvw_mcuauto_request_ids', 'name'=>'update_zzvw_mcuauto_request_ids', 'label'=>'Dp Requests', 
				'editable'=>true, 'type'=>'cart', 'cart_table'=>'zzvw_mcuauto_request', 'cart_db'=>'mydb', 
				'defval'=>$row['zzvw_mcuauto_request_ids'], 'editoptions'=>array('value'=>$data) ),
		);
		return $view_params;
	}
	
	protected function handlePost($params){
print_r("still in progressing");
		//process run_result
	}
}

?>