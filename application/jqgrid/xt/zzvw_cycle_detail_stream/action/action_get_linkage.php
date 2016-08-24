<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_get_linkage.php');
	
class xt_zzvw_cycle_detail_stream_action_get_linkage extends xt_zzvw_cycle_detail_action_get_linkage{
	
	protected function get_module(){
		$params = $this->params;
		$sql = "SELECT DISTINCT codec_stream_format.id as id, codec_stream_format.name as name FROM cycle_detail".
			" LEFT JOIN codec_stream ON cycle_detail.codec_stream_id=codec_stream.id".
			" LEFT JOIN codec_stream_format ON codec_stream.codec_stream_format_id=codec_stream_format.id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "cycle_detail.cycle_id=".$params['value'];
		}
		$where .= " AND codec_stream_format.name is not null";
		$sql .= " WHERE $where ORDER BY codec_stream_format.name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
		
	protected function get_testcase_priority(){
		$params = $this->params;
		$sql =  "SELECT DISTINCT testcase_priority.id as id, testcase_priority.name as name FROM cycle_detail".
			" LEFT JOIN codec_stream ON cycle_detail.codec_stream_id=codec_stream.id".
			" LEFT JOIN testcase_priority ON codec_stream.testcase_priority_id=testcase_priority.id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "cycle_detail.cycle_id=".$params['value'];
		}
		$where .= " AND testcase_priority.name is not null";
		$sql .= " WHERE $where ORDER BY testcase_priority.name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
	
	protected function get_stream_type(){
		$params = $this->params;
		$sql = "SELECT DISTINCT codec_stream_type.id as id, codec_stream_type.name as name FROM cycle_detail".
			" LEFT JOIN codec_stream ON cycle_detail.codec_stream_id=codec_stream.id".
			" LEFT JOIN codec_stream_type ON codec_stream.codec_stream_type_id=codec_stream_type.id";
		$where = "1";
		if(!empty($params['value']) && $params['value']){
			$where = "cycle_detail.cycle_id=".$params['value'];
		}
		$where .= " AND codec_stream_type.name is not null";
		$sql .= " WHERE $where ORDER BY codec_stream_type.name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
	
	protected function get_stream_format(){
		$params = $this->params;
		$sql = "SELECT DISTINCT codec_stream_format.id as id, codec_stream_format.name as name FROM cycle_detail".
			" LEFT JOIN codec_stream ON cycle_detail.codec_stream_id=codec_stream.id".
			" LEFT JOIN codec_stream_format ON codec_stream.codec_stream_format_id=codec_stream_format.id";
		$where = "1";
		if(!empty($params['parent']) && $params['parent']){
			$where .= " AND cycle_detail.cycle_id=".$params['parent'];
		}
		if(!empty($params['value']) && $params['value']){
			$where .= " AND codec_stream.codec_stream_type_id=".$params['value'];
		}
		$where .= " AND codec_stream_format.name is not null";
		$sql .= " WHERE $where ORDER BY codec_stream_format.name ASC";
		$res = $this->tool->query($sql);
		return json_encode($res->fetchAll());
	}
}
?>
