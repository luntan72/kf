<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_jqgrid.php');

class xt_zzvw_cycle_detail_action_set_tester extends xt_zzvw_cycle_detail_action_jqgrid{

   public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$element = $this->caclIDs($params);
		if($element == "error")
			return "error";
			
		$res = $this->tool->query("SELECT id FROM cycle_detail WHERE id in (".implode(',', $element).")");
		$i = 0;
		if($params['select_item'] == '-1')
			$params['select_item'] = 0;
		while($row = $res->fetch()){
			$i ++;
			$this->tool->update('cycle_detail', array('tester_id'=>$params['select_item']), 'id='.$row['id']);
		}
		if(count($element) == $i)
			return 'success';
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['type'] = 'Testor';
		$view_params['view_file'] = 'select_item.phtml';
		$view_params['view_file_dir'] = '/jqgrid/view';
		$view_params['blank'] = 'false';
		$res = $this->tool->query("select id, name from result_type");
		$sql = "SELECT tester_ids FROM cycle WHERE id = ".$params['parent'];
		$res = $this->tool->query($sql);
		$userIds = $res->fetch();
		$userList= $this->userAdmin->getUserList(array('id'=>$userIds['tester_ids']));
		$view_params['items'][0] = array('id'=>0, 'name'=>'');
		foreach($userList as $id=>$name)
			$view_params['items'][$id] = compact('id', 'name');
		$view_params['items'][-1] = array('id'=>'-1', 'name'=>'==Blank==');
		return $view_params;
	}
	
}

?>