<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_set_result.php');

class xt_zzvw_cycle_detail_stream_action_set_result extends xt_zzvw_cycle_detail_action_set_result{
	protected function returnData($data){
		$data['codec_stream_result'] = 'All Blank';
		$res = $this->tool->query("select id, name from result_type where id=".$data['result_type_id']);
		if($info = $res->fetch())
			$data['codec_stream_result'] = 'All '.$info['name'];
		if(!empty($data['updater_id']))
			unset($data['updater_id']);
		if(!empty($data['tester_id']))
			unset($data['tester_id']);
		$datas['statistics'] = $this->getStatistics(True);
		$datas['data'] = $data;		
		return json_encode($datas);
	}
}

?>